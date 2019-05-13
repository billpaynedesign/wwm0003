<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    /**
     * The models table
     */
    protected $table = 'shoppingcart_items';

    public function cart(){
    	return $this->belongsTo('App\ShoppingCart');
    }
    public function product(){
    	return $this->belongsTo('App\Product');
    }
    public function uom(){
    	return $this->belongsTo('App\UnitOfMeasure');
    }
}
