<?php

namespace App\Admin\Controllers;

use App\Admin\Selectors\GroupSelector;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Contact;

class ContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Contact';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Contact());
        $grid->model()
            ->addSelect('contacts.*')
            ->addSelect(DB::raw('CONCAT(name, " ", COALESCE(last_name, "")) as full_name'))
        ;
        $grid->column('full_name', __('Contact name'));
        $grid->column('company_name', __('Company'));
        $grid->column('customer.name', __('Customer associated'));
        $grid->column('email', __('Email'));
        $grid->groups()->display(function($groups) {
            $groups = array_map(function ($group) {
                return "<span class='badge bg-primary'>{$group['name']}</span>";
            }, $groups);

            return join('&nbsp;', $groups);
        });

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
        $show = new Show(Contact::findOrFail($id));

        $show->field('name', __('Name'));
        $show->field('last_name', __('Last name'));
        $show->field('company_name', __('Company'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('customer.name', __('Customer associated'));
        $show->field('bounced_at', __('Bounced at'));
        $show->field('unsubscribed_at', __('Unsubscribed at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Contact());
        /** @var User $user */
        $user = Auth::user();
        if($user->isRole('administrator')) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }
        $form->text('name', __('Name'))->required();
        $form->text('last_name', __('Last name'));
        $form->text('company_name', __('Company'))->required();
        $form->email('email', __('Email'))->required();
        $form->select('customer_id', __('Customer'))
            ->required()
            ->options($customers)
            ->default(array_slice($customers, 0, 1));
        $form->phonenumber('phone', __('Phone'));
        if($form->isEditing()) {
            $form->datetime('bounced_at', __('Bounced at'));
            $form->datetime('unsubscribed_at', __('Unsubscribed at'));
        }
        $form->belongsToMany('groups', GroupSelector::class, __('Groups'));

        return $form;
    }
}
