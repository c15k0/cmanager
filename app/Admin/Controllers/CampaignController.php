<?php

namespace App\Admin\Controllers;

use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class CampaignController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'cm.campaigns.title';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Campaign());

        $grid->column('code', __('cm.campaigns.code'));
        $grid->column('title', __('cm.campaigns.name'));
        $grid->column('template.name', __('cm.campaigns.used_template'));
        $grid->column('status', __('cm.campaigns.current_status'))->using(Campaign::getStatuses());

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Campaign::findOrFail($id));
        $show->field('code', __('cm.campaigns.code'));
        $show->field('title', __('cm.campaigns.name'));
        $show->field('customer.name', __('cm.campaigns.customer'));
        $show->field('template.name', __('cm.campaigns.used_template'));
        $show->field('status', __('cm.campaigns.current_status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Campaign());
        /** @var User $user */
        $user = Auth::user();
        if($user->isRole('administrator')) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }
        $prefix = config('database.connections.mysql.prefix');

        $form->tab(__('cm.campaigns.general'), function($form) use ($customers, $prefix) {
            $templates = Template::query()
                ->join('customers as c', 'c.id', '=', 'templates.customer_id')
                ->whereNull("c.deleted_at")
                ->whereIn('templates.customer_id', array_keys($customers))
                ->select([
                    DB::raw("CONCAT('[', {$prefix}c.name, '] ', {$prefix}templates.name) as template_name"),
                    'templates.id as tid',
                ])->pluck('template_name', 'tid')->toArray();

            $form->select('customer_id', __('cm.campaigns.customer'))
                ->required()
                ->options($customers)
                ->default(array_slice($customers, 0, 1));
            $form->select('template_id', __('cm.campaigns.template'))
                ->required()
                ->options($templates);

            $form->text('code', __('cm.campaigns.code'))
                ->help(__('cm.campaigns.helps.code'))
                ->required();
            $form->text('title', __('cm.campaigns.name'))->required();
            $form->datetime('start_at', __('cm.campaigns.start_date'))
                ->help(__('cm.campaigns.helps.star_date'));
        })->tab(__('cm.campaigns.receivers'), function($form) {

        });

        $form->disableViewCheck();
        $form->disableCreatingCheck();

        return $form;
    }
}
