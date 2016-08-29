<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailBox extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orderdetail_boxes';
	
	public function order(){
		return $this->belongsTo('App\Order');
	}
	public function details(){
		return $this->hasMany('App\Detail');
	}
}
