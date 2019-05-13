<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'attn',
        'address',
        'address2',
        'city',
        'state',
        'zip'
    ];

	public function products(){
		return $this->belongsToMany('App\Product');
	}
    public function bills(){
        return $this->hasMany('App\VendorBill');
    }
    public function purchase_orders(){
        return $this->hasMany('App\VendorPurchaseOrder');
    }
    public function getFullAddressAttribute(){
        $full_address = '';
        if($this->address){
            $full_address .= $this->address;
        }
        if($this->address2){
            $full_address .= ' '.$this->address2;
        }
        if($this->city){
            $full_address .= ' '.$this->city;
        }
        if($this->state){
            $full_address .= ', '.$this->state;
        }
        if($this->zip){
            $full_address .= ' '.$this->zip;
        }
        return $full_address;
    }
}
