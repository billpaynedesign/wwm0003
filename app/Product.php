<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements SluggableInterface{

	use SluggableTrait;
    use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

	protected $sluggable = [
	'build_from' => 'name',
	'save_to'    => 'slug',
	];
    protected $dates = ['deleted_at'];

    public function groups(){
        return  $this->belongsToMany('App\ProductGroup','product_has_product_groups','product_id','product_group_id');
    }
    public function options(){
        return $this->belongsToMany('App\Option','product_has_options','product_id','option_id');
    }
    public function user_price(){
        return $this->hasMany('App\UserPricing', 'id', 'product_id', 'user_has_pricing');
    }
    public function user_price_check($user_id){
        return $this->user_price()->where('user_id',$user_id)->first();
    }
    public function units_of_measure(){
    	return $this->hasMany('App\UnitOfMeasure');
    }
    public function pictures(){
    	return $this->hasMany('App\Picture','key','id');
    }
    public function orderDetails(){
    	return $this->hasMany('App\OrderDetails');
    }
    public function orders(){
    	return $this->hasManyThrough('App\Order','App\OrderDetails','id','product_id');
    }
    /*public function category(){
        return $this->belongsTo('App\Category');
    }*/
    public function getCategoryAttribute(){
        return $this->categories()->first();
    }
    public function categories(){
        return $this->belongsToMany('App\Category');
    }
    public function reviews(){
        return $this->hasMany('App\Review');
    }
    public function getPriceStringAttribute(){
        return '$'.\number_format((float)$this->price,2);
    }
    public function getMinPriceAttribute(){
        $min_uom = $this->units_of_measure()->orderBy('price','desc')->first();
        if($min_uom){
            return $min_uom->price;
        }
        else{
            return false;
        }
    }
    public function getMinPriceStringAttribute(){
        return '$'.\number_format((float)$this->min_price,2);
    }
    public function getMinMsrpAttribute(){
        $min_uom = $this->units_of_measure()->orderBy('msrp','asc')->first();
        if($min_uom){
            return $min_uom->msrp;
        }
        else{
            return false;
        }
    }
    public function getMinMsrpStringAttribute(){
        return '$'.\number_format((float)$this->min_msrp,2);
    }
    public function scopeActive($query){
        return $query->where('active',1);
    }
    public function categoryBreadcrumbs(){
    	$html = array();
    	$category = $this->category;

    	while (!is_null($category)){
    		$html[] = '<li><a href="'. route('category-show', $category->slug) .'">'. $category->name .'</a></li>';
    		$category = $category->parent;
    	}
    	krsort($html);
    	return implode($html);
    }
}
