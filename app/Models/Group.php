<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function bounced_contacts() {
        return $this->contacts()->whereNotNull('bounced_at');
    }

    public function unsubscribed_contacts() {
        return $this->contacts()->whereNotNull('unsubscribed_at');
    }
}
