<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionGroup extends Model
{
    use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'option_groups';
	protected $dates = ['deleted_at'];

	public function product_groups(){
		return $this->hasMany('App\ProductGroup');
	}

	public function options(){
		return $this->hasMany('App\Option');
	}
}
