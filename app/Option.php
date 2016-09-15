<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'options';

    public function products(){
        return $this->belongsToMany('App\Product','product_has_options','option_id','product_id');
    }

    public function option_group(){
    	return $this->belongsTo('App\OptionGroup');
    }
}
