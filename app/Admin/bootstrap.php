<?php

/**
 * Open-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * OpenAdmin\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * OpenAdmin\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;

OpenAdmin\Admin\Form::forget(['editor']);
/** @var User $user */
$user = Auth::user();

Form::init(function (Form $form) use ($user) {
    $form->disableEditingCheck();

    $form->disableCreatingCheck();

    $form->disableViewCheck();

    $form->tools(function (Form\Tools $tools) use ($user) {
        if(!$user->isAdministrator()) {
            $tools->disableDelete();
        }
        $tools->disableView();
    });
});

Grid::init(function (Grid $grid) use ($user) {
    $grid->tools(function (Grid\Tools $tools) {
        $tools->disableBatchActions();
    });
    $grid->disableExport();
    $grid->actions(function (Grid\Displayers\Actions\Actions $actions) use ($user) {
        $actions->disableShow();
        $actions->disableView();
        if(!$user->isAdministrator()) {
            $actions->disableDelete();
        }
    });
    $grid->filter(function(Grid\Filter $filter) {
        $filter->disableIdFilter();
    });
});
