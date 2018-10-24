<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillable = ['name','tax'];

    protected $casts = [
        'float' => 'tax'
    ];

    public function users(){
        return $this->hasMany('App\User');
    }
}
