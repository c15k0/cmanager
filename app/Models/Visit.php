<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        "ip_address", "user_agent", "customer_id", "campaign_id",
    ];

    protected function receiver() {
        return $this->belongsTo(Receiver::class);
    }

    protected function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    protected function customer() {
        return $this->belongsTo(Customer::class);
    }
}
