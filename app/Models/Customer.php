<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAdmin\Admin\Auth\Database\Administrator;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'config',
    ];

    protected $casts = [
        'json' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function users() {
        return $this->belongsToMany(Administrator::class, 'customers_users', 'customer_id', 'user_id');
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }
}
