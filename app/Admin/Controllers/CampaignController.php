<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\CancelCampaignAction;
use App\Admin\Actions\ChangeCampaignAction;
use App\Admin\Actions\LaunchCampaignAction;
use App\Admin\Actions\RetryFailedEmailsAction;
use App\Admin\Selectors\ContactSelector;
use App\Admin\Selectors\GroupSelector;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Receiver;
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

    public function __construct()
    {
        $this->title = __('cm.campaigns.title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Campaign());
        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdministrator() || $user->customers()->count() > 1) {
            $grid->column('customer.name', __('cm.contacts.customer_associated'));
        }
        if(!$user->isAdministrator()) {
            $grid->model()->whereIn('customer_id', $user->customers()->pluck('customers.id'));
        }
        $grid->column('title', __('cm.campaigns.name'));
        $grid->column('template.name', __('cm.campaigns.used_template'));
        $grid->column('status', __('cm.campaigns.current_status'))
            ->using(Campaign::getStatuses())
            ->dot([
                'created' => 'info',
                'ready' => 'primary',
                'sending' => 'primary',
                'sent' => 'success',
                'error' => 'danger',
                'cancelled' => 'warning',
            ], 'info');
        $grid->column('groups', __('cm.campaigns.groups'))->display(function ($groups) {
            $str = '';
            foreach ($groups as $group) {
                $str .= '<span class="badge rounded-pill bg-success">' . $group['name'] . '</span> ';
            }
            return $str;
        });
        $grid->column('start_at', __('cm.campaigns.start_date'));
        $grid->model()->addSelect('*')
            ->addSelect('id as sid')
            ->addSelect('id as rid')
            ;
        $grid->column('sid', __('cm.campaigns.sent_emails'))
            ->display(fn($id) => Receiver::query()
                ->where('campaign_id', $id)
                ->where('status', 'sent')
                ->count()
            );
        $grid->column('rid', __('cm.campaigns.read_emails'))
            ->display(fn($id) => Receiver::query()
                ->where('campaign_id', $id)
                ->where('status', 'sent')
                ->whereNotNull('first_opened_at')
                ->count()
            );

        $grid->filter(function(Grid\Filter $filter) {
            $filter->in('groups.group_id', __('cm.campaigns.groups'))
                ->multipleSelect(Group::query()->pluck('name', 'id')->toArray());
            $filter->in('status', __('cm.campaigns.current_status'))
                ->multipleSelect(Campaign::getStatuses());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->disableBatchActions(false);
            $tools->batch(function($action) {
                $action->add(__('cm.campaigns.actions.cancel_campaigns'), new CancelCampaignAction);
                $action->add(__('cm.campaigns.actions.launch_campaigns'), new LaunchCampaignAction);
                $action->add(__('cm.campaigns.actions.retry_failed_emails'), new RetryFailedEmailsAction);
            });
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
        if ($user->isAdministrator()) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }
        $prefix = config('database.connections.mysql.prefix');

        $form->tab(__('cm.campaigns.general'), function ($form) use ($customers, $prefix) {
            if(count($customers) > 1) {
                $form->select('customer_id', __('cm.groups.customer_name'))
                    ->required()
                    ->options($customers)
                    ->default(array_slice($customers, 0, 1));
            } else {
                $form->hidden('customer_id')->default(array_key_first($customers))->value(array_key_first($customers));
            }
            $templates = Template::query()
                ->join('customers as c', 'c.id', '=', 'templates.customer_id')
                ->whereNull("c.deleted_at")
                ->whereIn('templates.customer_id', array_keys($customers))
                ->select([
                    DB::raw("CONCAT('[', {$prefix}c.name, '] ', {$prefix}templates.name) as template_name"),
                    'templates.id as tid',
                ])->pluck('template_name', 'tid')->toArray();

            $form->select('template_id', __('cm.campaigns.template'))
                ->required()
                ->options($templates);

            $form->text('code', __('cm.campaigns.code'))
                ->help(__('cm.campaigns.helps.code'))
                ->required();
            $form->text('title', __('cm.campaigns.name'))->required();
            $form->datetime('start_at', __('cm.campaigns.start_date'))
                ->help(__('cm.campaigns.helps.start_date'));
        })->tab(__('cm.campaigns.receivers'), function ($form) {
            $form->belongsToMany('groups', GroupSelector::class, __('cm.contacts.group'));
            $form->belongsToMany('contacts', ContactSelector::class, __('cm.contacts.title'));
        });
        if($form->isEditing()) {
            $campaign = Campaign::query()->find(request()->route('campaign'));
            if(in_array($campaign?->status, ['ready', 'sending', 'sent', 'error'])) {
                $form->tab(__('cm.campaigns.template_history'), function($form) use ($campaign) {
                    $form->html($campaign->label, __('cm.templates.label'))->readonly();
                    $form->html($campaign->raw, __('cm.templates.content'))->readonly();
                });
            }
        }

        $form->disableViewCheck();
        $form->disableCreatingCheck();

        return $form;
    }
}
