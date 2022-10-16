<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'customer_id',
        'invoice_number',
        'hamali_charges',
        'net_amount',
        'billing_date',
        'transporter_name',
        'dispatched_date'
    ];
    
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    
    public function invoice_detail(){
        return $this->hasMany(InvoiceDetail::class);
    }
}
