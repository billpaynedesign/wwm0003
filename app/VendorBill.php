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
        'term_id',
        'paid'
    ];
    protected $dates = ['date'];
    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }
    public function payment_term(){
        return $this->belongsTo('App\PaymentTerm');
    }
    public function getAmountStringAttribute(){
        return '$'.number_format((float)$this->amount, 2);
    }
}
