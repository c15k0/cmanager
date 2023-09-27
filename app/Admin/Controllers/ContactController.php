<?php

namespace App\Admin\Controllers;

use App\Admin\Selectors\GroupSelector;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Actions\Action;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class ContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Contact';

    public function __construct() {
        $this->title = __('cm.contacts.title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        /** @var User $user */
        $user = Auth::user();
        $grid = new Grid(new Contact());
        $grid->model()
            ->addSelect('contacts.*')
            ->addSelect(DB::raw('CONCAT(name, " ", COALESCE(last_name, "")) as full_name'))
        ;
        if ($user->isAdministrator() || $user->customers()->count() > 1) {
            $grid->column('customer.name', __('cm.contacts.customer_associated'));
        }
        if(!$user->isAdministrator()) {
            $grid->model()->whereIn('customer_id', $user->customers()->pluck('customers.id'));
        }
        $grid->column('full_name', __('cm.contacts.name'));
        $grid->column('company_name', __('cm.contacts.company'));
        $grid->column('email', __('cm.contacts.email'));
        $grid->groups()->display(function($groups) {
            $groups = array_map(function ($group) {
                return "<span class='badge bg-primary'>{$group['name']}</span>";
            }, $groups);

            return join('&nbsp;', $groups);
        });

        $grid->filter(function (Grid\Filter $filter) {
            $filter->contains('name', __('cm.contacts.name'));
            $filter->contains('company_name', __('cm.contacts.company'));
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

        $show->field('name', __('cm.contacts.name'));
        $show->field('last_name', __('cm.contacts.last_name'));
        $show->field('company_name', __('cm.contacts.company'));
        $show->field('email', __('cm.contacts.email'));
        $show->field('phone', __('cm.contacts.phone'));
        $show->field('customer.name', __('cm.contacts.customer_associated'));
        $show->field('bounced_at', __('cm.contacts.bounced_at'));
        $show->field('unsubscribed_at', __('cm.contacts.unsubscribed_at'));
        $show->field('created_at', __('cm.created_at'));
        $show->field('updated_at', __('cm.updated_at'));

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
        if ($user->isAdministrator()) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }
        if(count($customers) > 1) {
            $form->select('customer_id', __('cm.groups.customer_name'))
                ->required()
                ->options($customers)
                ->default(array_slice($customers, 0, 1));
        } else {
            $form->hidden('customer_id')->default(array_key_first($customers));
        }
        $form->text('name', __('cm.contacts.name'))->required();
        $form->text('last_name', __('cm.contacts.last_name'));
        $form->email('email', __('cm.contacts.email'))->required();
        $form->text('company_name', __('cm.contacts.company'))
            ->required();
        $form->phonenumber('phone', __('cm.contacts.phone'));
        if($form->isEditing()) {
            $form->datetime('bounced_at', __('cm.contacts.bounced_at'))->readonly();
            $form->datetime('unsubscribed_at', __('cm.contacts.unsubscribed_at'))->readonly();
        }
        $form->belongsToMany('groups', GroupSelector::class, __('cm.contacts.group'));

        return $form;
    }
}
