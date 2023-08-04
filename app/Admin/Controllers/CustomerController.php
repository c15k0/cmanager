<?php

namespace App\Admin\Controllers;

use App\Admin\Selectors\UserSelector;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Customer;

class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->model()->withTrashed();

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

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
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('configuration', __('Configuration'))->json();
        $show->field('signature', __('Signature'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Customer());

        $form->text('name', __('Name'));
        $form->textarea('configuration', __('Configuration'));
        $form->ckeditor('signature', __('Signature'));
        $form->belongsToMany('users', UserSelector::class, __('Users'));

        return $form;
    }
}
