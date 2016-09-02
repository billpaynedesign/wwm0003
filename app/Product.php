<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Product extends Model implements SluggableInterface{

	use SluggableTrait;
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

    public function user_price(){
        $this->hasMany('App\UserPricing', 'id', 'product_id', 'user_has_pricing');
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
    public function category()
    {
    	return $this->belongsTo('App\Category');
    }
    public function reviews(){
        return $this->hasMany('App\Review');
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
        $min_uom = $this->units_of_measure()->orderBy('msrp','desc')->first();
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
