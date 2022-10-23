<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    protected $fillable = [
        'id',
        'billing_id',
        'amount',
        'return_date'
    ];

    public function billing() {
        return $this->belongsTo(Billing::class);
    }
}
