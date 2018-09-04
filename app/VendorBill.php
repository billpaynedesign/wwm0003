<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorBill extends Model
{
    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }
}
