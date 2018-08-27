<?php namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use QBItem;

class Product extends Model{

	use Sluggable;
    use SoftDeletes;
    use SluggableScopeHelpers;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

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
    public function scopeFeatured($query){
        return $query->where('featured',1);
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
    public function qbCheckOrCreate($dataService){
        if($this->qb_id){
            $entities = $dataService->Query("select * from Item where Id='{$this->qb_id}'");
            if($entities != null){
                if(!empty($entities) && sizeof($entities) == 1){
                    $item = current($entities);
                    return $item;
                }
            }
        }
        $item = QBItem::create([
            "Type" => "NonInventory",
            "Name" => $this->name,
            "Sku" => $this->item_number,
            "IncomeAccountRef" => [
                "value" => "79",
                "name" => "Sales of Product Income"
            ],
            "ExpenseAccountRef" => [
                "value" => "80",
                "name" => "Cost of Goods Sold"
            ]
        ]);
        $response = $dataService->Add($item);
        if (null != $error = $dataService->getLastError()) {
            $errormessage = "Item Creation Error: \n";
            $errormessage .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
            $errormessage .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            $errormessage .= "The Response message is: " . $error->getResponseBody() . "\n";
            Log::error($errormessage);
            return false;
        }
        $this->qb_id = $response->Id;
        $this->save();
        return $response;
    }
}
