<?php

namespace App\Admin\Selectors;

use App\Models\User;
use OpenAdmin\Admin\Grid\Filter;
use OpenAdmin\Admin\Grid\Selectable;

class UserSelector extends Selectable {

    public $model = User::class;

    public function make()
    {
        $this->column('username', trans('admin.username'));
        $this->column('name', trans('admin.name'));

        $this->filter(function(Filter $filter) {
            $filter->like('username', trans('admin.username'));
            $filter->like('name', trans('admin.name'));
        });
    }

}
