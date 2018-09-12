<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorBill extends Model
{
    protected $fillable = [
        'vendor_id',
        'date',
        'reference_num',
        'amount',
        'payment_term_id',
        'paid',
        'bill_account_id'
    ];
    protected $dates = ['date'];

    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }
    public function payment_term(){
        return $this->belongsTo('App\PaymentTerm');
    }
    public function bill_account(){
        return $this->belongsTo('App\BillAccount');
    }
    public function getAmountStringAttribute(){
        return '$'.number_format((float)$this->amount, 2);
    }
    public function getPaidIconAttribute(){
        return $this->paid?'<span class="fa fa-check"></span>':'';
    }
}
