<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $fillable = ['name', 'days'];

    public function vendor_bills(){
        return $this->hasMany('App\VendorBill');
    }
}
