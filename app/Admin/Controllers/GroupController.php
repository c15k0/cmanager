<?php

namespace App\Admin\Controllers;

use App\Admin\Selectors\ContactSelector;
use App\Models\Customer;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class GroupController extends AdminController
{
    public function __construct()
    {
        $this->title = __('cm.groups.title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Group());
        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdministrator() || $user->customers()->count() > 1) {
            $grid->column('customer.name', __('cm.groups.customer_name'));
        }
        if(!$user->isAdministrator()) {
            $grid->model()->whereIn('customer_id', $user->customers()->pluck('customers.id'));
        }
        $grid->column('name', __('cm.groups.name'));
        $grid->column('contacts', __('cm.groups.contacts_count'))
            ->display(fn($contacts) => count($contacts));
        $grid->column('bounced_contacts', __('cm.groups.bounced_count'))
            ->display(fn($contacts) => count($contacts));
        $grid->column('unsubscribed_contacts', __('cm.groups.unsubscribed_count'))
            ->display(fn($contacts) => count($contacts));

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
        $show = new Show(Group::findOrFail($id));

        $show->field('name', __('cm.groups.name'));
        $show->field('customer.name', __('cm.groups.customer_name'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Group());
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
        $form->text('name', __('cm.groups.name'))->required();
        $form->belongsToMany('contacts', ContactSelector::class, __('cm.contacts.title'));

        return $form;
    }
}
