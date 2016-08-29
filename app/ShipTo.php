<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipTo extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_shiptos';

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function getIdStringAttribute(){
		return sprintf('%07d', $this->id);
	}
}
