<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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
	public function product_price(){
		return $this->hasMany('App\UserPricing', 'user_id', 'id', 'user_has_pricing');
	}
	public function product_price_check($product_id){
		return $this->product_price()->where('product_id',$product_id)->first();
	}
	public function getFrequentProductsAttribute(){
		$products = collect([]);
		$quantities = [];
		foreach ($this->orders as $order) {
			foreach ($order->details as $detail) {
				if(!($products->contains($detail->product))){
					$quantities[$detail->product->id] = OrderDetail::where('order_id',$order->id)->where('product_id',$detail->product->id)->sum('quantity');
					$products->push($detail->product);
				}
			}
		}
		return compact('products','quantities');
	}
}
