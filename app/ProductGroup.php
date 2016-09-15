<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'product_groups';
	public $timestamps = false;

	public function option_group(){
		return $this->belongsTo('App\OptionGroup');
	}
    public function products(){
        return $this->belongsToMany('App\Product','product_has_product_groups','product_group_id','product_id');
    }
}
