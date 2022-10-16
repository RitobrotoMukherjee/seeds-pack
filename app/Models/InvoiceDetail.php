<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'billing_id',
        'product_id',
        'quantity',
        'unit_price'
    ];
    
    public function billing(){
        return $this->belongsTo(Billing::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    
}
