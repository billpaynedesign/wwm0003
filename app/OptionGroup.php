<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'option_groups';

	public function product_groups(){
		return $this->hasMany('App\ProductGroup');
	}

	public function options(){
		return $this->hasMany('App\Option');
	}
}
