<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductpurchaseDetail extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'product_id',
        'purchase_from',
        'purchase_date',
        'purchase_quantity',
        'purchase_price_per_kg',
    ];
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
