<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorPurchaseOrder extends Model
{
    protected $fillable = [
        'vendor_id',
        'date',
        'total'
    ];
    protected $casts = [
        'vendor_id' => 'integer',
        'total' => 'float'
    ];

    protected $dates = ['date'];

    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }
    public function details(){
        return $this->hasMany('App\VendorPoDetail');
    }
    public function getInvoiceNumAttribute(){
        return sprintf("%07d", $this->id);
    }
}
