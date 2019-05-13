<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillAccount extends Model
{
    protected $fillable = ['name'];

    public function bills(){
        return $this->hasMany('App\VendorBill');
    }
}
