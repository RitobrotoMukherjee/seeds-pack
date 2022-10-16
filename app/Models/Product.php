<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'type',
        'available',
        'sale_price_per_kg'
    ];
    
    public function productpurchase_detail() {
        return $this->hasMany(ProductpurchaseDetail::class)->orderBy('id', 'DESC')->limit(30);
    }
    
    public function billing() {
        return $this->hasMany(Billing::class);
    }
}
