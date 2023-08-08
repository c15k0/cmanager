<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "code", "title", "start_at", "template_id", "customer_id", "status",
    ];

    public static function getStatuses() {
        return [
            'created' => __('cm.campaigns.status.created'),
            'ready' => __('cm.campaigns.status.ready'),
            'sending' => __('cm.campaigns.status.sending'),
            'sent' => __('cm.campaigns.status.sent'),
            'error' => __('cm.campaigns.status.error'),
            'cancelled' => __('cm.campaigns.status.cancelled'),
        ];
    }

    public function template() {
        return $this->belongsTo(Template::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function receivers() {
        return $this->hasMany(Receiver::class);
    }
}
