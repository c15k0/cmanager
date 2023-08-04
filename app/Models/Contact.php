<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'last_name', 'company_name', 'phone', 'email', 'bounced_at', 'unsubscribed_at',
    ];

    protected $casts = [
        'bounced_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function groups(): BelongsToMany {
        return $this->belongsToMany(Group::class, 'groups_contacts', 'contact_id', 'group_id');
    }
}
