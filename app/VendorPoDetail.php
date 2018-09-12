<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorPoDetail extends Model
{
    protected $fillable = ['quantity','product_id','uom_id','item_total'];

    public function vendor_purchase_order(){
        return $this->belongsTo('App\VendorPurchaseOrder');
    }
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function uom(){
        return $this->belongsTo('App\UnitOfMeasure');
    }
}
