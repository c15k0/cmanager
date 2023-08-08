<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    protected $fillable = [
        "campaign_id", "contact_id", "hash", "status", "error", "first_opened_at", "last_opened_at",
    ];

    public static function getStatuses() {
        return [
            'created' => __('admin.receiver.status.created'),
            'sent' => __('admin.receiver.status.sent'),
            'error' => __('admin.receiver.status.error'),
        ];
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function contact() {
        return $this->belongsTo(Contact::class);
    }

    public function visits() {
        return $this->hasMany(Visit::class);
    }
}
