<?php

namespace App\Admin\Controllers;

use App\Models\Receiver;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class ReceiverController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Receiver';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Receiver());
        $grid->column('campaign.title', 'Campaign');
        $grid->column('contact.email', 'Email');
        $grid->column('status', 'Estado');
        $grid->column('first_opened_at', 'Lectura');
        $grid->column('error', 'Error')->limit(25);
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
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
        $show = new Show(Receiver::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Receiver());



        return $form;
    }
}
