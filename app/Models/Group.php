<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAdmin\Admin\Auth\Database\Administrator;

class Group extends Model
{
    use SoftDeletes;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contacts() {
        return $this->belongsToMany(Contact::class, 'groups_contacts', 'group_id', 'contact_id');
    }

}
