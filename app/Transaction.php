<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'transactions';

	public function order(){
		return $this->hasOne('App\Order');
	}

}
