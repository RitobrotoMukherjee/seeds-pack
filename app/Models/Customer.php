<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'mobile',
        'address',
        'city_village',
        'pincode'
    ];
    
    public function payment() {
        return $this->hasMany(Payment::class);
    }
    public function latest_payment() {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
    
    public function billing() {
        return $this->hasMany(Billing::class);
    }
}
