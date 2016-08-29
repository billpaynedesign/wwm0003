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
    public function productAttributes(){
    	return $this->hasMany('App\ProductAttribute');
    }
    public function uniqueAttributeNames(){
    	$names = array();
    	foreach($this->productAttributes as $attribute){
    		$names[$attribute->name] = NULL;
    	}
    	return $names;
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
    public function getPriceStringAttribute(){
        return '$'.\number_format((float)$this->price,2);
    }
    public function getMsrpStringAttribute(){
        return '$'.\number_format((float)$this->msrp,2);        
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
