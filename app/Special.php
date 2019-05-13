<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Special extends Model
{
    protected $table = 'specials';

    public static function seed(){
    	$special = new Special;
    	$special->header = '';
    	$special->secondary = '';
    	$special->url = '';
    	$special->save();
    	return $special;
    }

    public function isValid(){
    	if(!empty($this->id)){
    		if(!empty(trim($this->header)) && !empty(trim($this->secondary)) && !empty(trim($this->url))){
    			return true;
    		}
    	}
    	return false;
    }
}
