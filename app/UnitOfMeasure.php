<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model {

	protected $table = 'units_of_measure';

	public function products()
    {
        return $this->belongsTo('App\Product');
    }
    public function getPriceStringAttribute(){
        return '$'.\number_format((float)$this->price,2);
    }
    public function getMsrpStringAttribute(){
        return '$'.\number_format((float)$this->msrp,2);
    }
}
