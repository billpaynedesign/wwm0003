<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPricing extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_has_pricing';

	public function product(){
		return $this->belongsTo('App\Product');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

    public function getPriceStringAttribute(){
        return '$'.\number_format($this->price,2);
    }

}
