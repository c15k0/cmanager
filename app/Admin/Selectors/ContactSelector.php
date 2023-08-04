<?php

namespace App\Admin\Selectors;

use App\Models\Contact;
use OpenAdmin\Admin\Grid\Selectable;

class ContactSelector extends Selectable {
    public $model = Contact::class;

    public function make()
    {

    }

}
