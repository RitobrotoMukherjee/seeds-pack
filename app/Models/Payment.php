<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'customer_id',
        'payment_amount',
        'payment_date',
    ];
    
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
