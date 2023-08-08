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
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Group';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Group());

        $grid->column('name', __('Name'));
        $grid->column('customer.name', __('Customer'));

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

        $show->field('name', __('Name'));
        $show->field('customer.name', __('Customer'));

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
        if($user->isRole('administrator')) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }

        $form->text('name', __('Name'))->required();
        $form->select('customer_id', __('Customer'))
            ->required()
            ->options($customers)
            ->default(array_slice($customers, 0, 1));

        $form->belongsToMany('contacts', ContactSelector::class, __('Contacts'));

        return $form;
    }
}
