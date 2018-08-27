<?php namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model{

    use SoftDeletes;
	use Sluggable;
    use SluggableScopeHelpers;
    
	static $getCategorySelect;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

    /**
     * Sluggable configuration.
     *
     * @var array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    protected $dates = ['deleted_at'];
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	//protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = ['password', 'remember_token'];
	public function parent(){
		return $this->belongsTo('App\Category','parent_id');
	}
	public function children(){
		return $this->hasMany('App\Category','parent_id');
	}
    public function products(){
        return $this->belongsToMany('App\Product');
    }
	public function scopeFeatured($query){
		return $query->where('featured',1);
	}
	public function scopeActive($query){
		return $query->where('active',1);
	}
    public function getBreadcrumbs(){
    	$html = array();
    	$category = $this->parent;
    	if($category){
    		$html[] = '<li><a href="'. route('category-show', $this->slug) .'">'. $this->name .'</a></li>';
	    	while ($category){
	    		$html[] = '<li><a href="'. route('category-show', $category->slug) .'">'. $category->name .'</a></li>';
	    		$category = $category->parent;
	    	}
	    	krsort($html);
    		return implode($html);
	    }
	    else{
	    	return false;
	    }
    }
	public function childProducts(){
		$products = $this->products()->active()->get();
		if($this->children->count()>0){
			foreach ($this->children as $child) {
				$products = $products->merge($child->childProducts());
			}
		}
		return $products;
	}
	public function getProductsCount(){
		$total = 0;
		if($this->children->count()>0){
			foreach ($this->children as $child) {
				$total += $child->getProductsCount();
			}
		}
		else{
			$total += $this->products->count();
		}
		return $total;
	}
	public static function getCategorySelect(){
		$categories = self::all();
		$getCategorySelect = array();
		foreach ($categories as $category) {
			if($category->children->count()>0){
				$getCategorySelect[] = "";
			}
			else{
				$getCategorySelect[] = "<option value='".$category->id."'>".$category->name."</option>";
			}
		}
		return $getCategorySelect;
	}
}
