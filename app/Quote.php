<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rfq_num',
        'email',
        'billing_address1',
        'billing_address2',
        'billing_city',
        'billing_state',
        'billing_zip',
        'shipping_address1',
        'shipping_address2',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'status',
        'total'
    ];

    protected $dates = ['deleted_at'];

    public function getQuoteNumAttribute(){
        return sprintf("%07d", $this->id);
    }
    public function getFullBillingAddressAttribute(){
        $full_address = '';
        if($this->billing_address1){
            $full_address .= $this->billing_address1;
        }
        if($this->billing_address2){
            $full_address .= ' '.$this->billing_address2;
        }
        if($this->billing_city){
            $full_address .= ' '.$this->billing_city;
        }
        if($this->billing_state){
            $full_address .= ', '.$this->billing_state;
        }
        if($this->billing_zip){
            $full_address .= ' '.$this->billing_zip;
        }
        return $full_address;
    }
    public function getFullShippingAddressAttribute(){
        $full_address = '';
        if($this->shipping_address1){
            $full_address .= $this->shipping_address1;
        }
        if($this->shipping_address2){
            $full_address .= ' '.$this->shipping_address2;
        }
        if($this->shipping_city){
            $full_address .= ' '.$this->shipping_city;
        }
        if($this->shipping_state){
            $full_address .= ', '.$this->shipping_state;
        }
        if($this->shipping_zip){
            $full_address .= ' '.$this->shipping_zip;
        }
        return $full_address;
    }

    public function products(){
        return $this->hasMany('App\QuoteProduct');
    }
}
