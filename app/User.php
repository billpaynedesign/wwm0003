<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Log;
use QBCustomer;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['company','first_name','last_name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function getNameAttribute(){
		return $this->first_name.' '.$this->last_name;
	}
	public function orders(){
        return $this->hasMany('App\Order');
	}
	public function shipping(){
		return $this->hasMany('App\ShipTo');
	}
    public function carts(){
        return $this->hasMany('App\ShoppingCart');
    }
	public function product_price(){
		return $this->hasMany('App\UserPricing', 'user_id', 'id', 'user_has_pricing');
	}
	public function product_price_check($product_id){
		return $this->product_price()->where('product_id', $product_id)->first();
	}
	public function uom_price_check($uom_id) {
        return $this->product_price()->where('uom_id', $uom_id)->first();
    }
	public function getFrequentProductsAttribute(){
		$products = collect([]);
		$quantities = [];
		foreach ($this->orders as $order) {
			foreach ($order->details as $detail) {
				if($detail->product){
					if(!($products->contains($detail->product))){
						$quantities[$detail->product->id] = OrderDetail::where('order_id',$order->id)->where('product_id',$detail->product->id)->sum('quantity');
						$products->push($detail->product);
					}
				}
			}
		}
		return compact('products','quantities');
	}

    public function getCartAttribute(){
        return count($this->carts)?$this->carts()->orderBy('id', 'desc')->first():false;
    }

    public function qbCheckOrCreate($dataService){
    	if($this->qb_id){
	    	$entities = $dataService->Query("select * from Customer where Id='{$this->qb_id}'");
	    	if($entities != null){
				if(!empty($entities) && sizeof($entities) == 1){
				    $user = current($entities);
				    return $user;
				}
			}
		}
		
		$customer = QBCustomer::create([
		    "CompanyName" => $this->company,
		    "DisplayName" => $this->name,
		    "PrimaryPhone" => [
		        "FreeFormNumber" => $this->phone
		    ],
		    "PrimaryEmailAddr" => [
		        "Address" => $this->email
		    ]
		]);
		$response = $dataService->Add($customer);
		if (null != $error = $dataService->getLastError()) {
			$errormessage = "User Creation Error: \n";
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
