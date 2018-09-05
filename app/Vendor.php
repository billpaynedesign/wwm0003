<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone'
    ];

    public function products(){
        return $this->hasMany('App\Product');
    }
    public function bills(){
        return $this->hasMany('App\VendorBill');
    }
    public function purchase_orders(){
        return $this->hasMany('App\VendorPurchaseOrder');
    }
}
