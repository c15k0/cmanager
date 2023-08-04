<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Template;

class TemplateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Template';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Template());

        $grid->column('name', __('Name'));
        $grid->column('label', __('Título'));
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
        $show = new Show(Template::findOrFail($id));

        $show->field('name', __('Name'));
        $show->field('label', __('Title'));
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
        $form = new Form(new Template());
        /** @var User $user */
        $user = Auth::user();
        if($user->isRole('administrator')) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }
        $form->text('name', __('Name'))->required();
        $form->text('label', __('Título'))->required();
        $form->select('customer_id', __('Customer'))
            ->required()
            ->options($customers)
            ->default(array_slice($customers, 0, 1));
        $form->ckeditor('raw', __('Contenido'))->required();

        return $form;
    }
}
