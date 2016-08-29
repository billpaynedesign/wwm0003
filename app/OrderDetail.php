<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orderdetails';
	
	public function order(){
		return $this->belongsTo('App\Order');
	}
	public function product(){
		return $this->belongsTo('App\Product');
	}
}
