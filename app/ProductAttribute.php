<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'productattributes';

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

	public function pictures()
    {
        return $this->hasMany('App\Picture','key','id');
    }
    public function scopeActive($query){
    	return $query->where('active',1);
    }
}
