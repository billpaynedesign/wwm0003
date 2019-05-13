<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    protected $fillable = [
        'product_id',
        'uom_id',
        'quantity',
        'price',
        'item_total'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'uom_id' => 'integer',
        'quantity' => 'integer',
        'item_total' => 'float',
        'price' => 'float'
    ];

    public function quote(){
        return $this->belongsTo('App\Quote');
    }
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function uom(){
        return $this->belongsTo('App\UnitOfMeasure');
    }
}
