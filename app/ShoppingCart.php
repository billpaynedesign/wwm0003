<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ShoppingCart extends Model
{
    /**
     * The models table
     */
    protected $table = 'shoppingcart';

    protected $appends = ['count'];

    public function items(){
        return $this->hasMany('App\ShoppingCartItem');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    /**
     * Add an item to a cart instance
     * @param int $product_id Product id in DB
     * @param int $uom_id     UniteOfMeasure id in DB
     * @param int $quantity   Quantity of item
     * @param int $cart_id    Cart id in DB if setting an existing cart
     * @todo Add type checks and throw an error when parameter types don't match
     */
    public static function add($product_id, $uom_id, $quantity, $cart_id = null){

        //if we are just updating an existing cart and don't need to look for one or have it in session
        if($cart_id){
            $cart = self::find($cart_id);
        }
        else{
            //do we already have a cart in the session, if so we'll get it and update that one
            if(session()->has('cart_id')){
                $cart = self::find(session()->get('cart_id'));
            }
            else{
                //if we don't have a cart in the session lets check if the user is logged in and if they have a cart saved in the DB
                if(Auth::check()){
                    if($cart = self::where('user_id',Auth::user()->id)->first()){
                        session()->put('cart_id',$cart->id);
                    }
                    else{
                        //user is logged in and has no cart, so create a fresh cart and save it to the session
                        $cart = new self;
                        $cart->user_id = Auth::user()->id;
                        $cart->sub_total = 0;
                        $cart->save();
                        session()->put('cart_id',$cart->id);
                    }
                }
                else{
                    //if the user isn't logged in and no session cart, create a fresh cart and save it to the session
                    $cart = new self;
                    $cart->sub_total = 0;
                    $cart->save();
                    session()->put('cart_id',$cart->id);
                }
            }
        }

        $product = Product::find($product_id);
        $uom = UnitOfMeasure::find($uom_id);

        //check if this item already exists and we just need to update that item
        if($item = $cart->items()->where('product_id', $product_id)->where('uom_id', $uom_id)->first()){
            $item->quantity = $item->quantity+$quantity;
        }
        else{
            $item = new ShoppingCartItem;
            $item->shopping_cart_id = $cart->id;
            $item->quantity = $quantity;
            $item->uom_id = $uom_id;
            $item->product_id = $product_id;
        }

        $item_cost = $uom->price;
        if($cart_id){
            $user = User::find($cart->user_id);
            if($user->product_price_check($product_id)){
                if($price = $user->uom_price_check($uom_id)){
                    $item_cost = (float)$price->price;
                }
            }
        }
        else{
            if(Auth::check()){
                $user = Auth::user();
                if($user->product_price_check($product_id)){
                    if($price = $user->uom_price_check($uom_id)){
                        $item_cost = (float)$price->price;
                    }
                }
            }
        }

        $item->cost = $item_cost;
        $item->sub_total = round($item_cost*$quantity, 2, PHP_ROUND_HALF_UP);
        $item->save();

        $cart->sub_total = $cart->items()->sum('sub_total');
        $cart->save();
    }

    public static function count($cart_id = null){
        $count = 0;
        if($cart_id){
            $cart = self::find($cart_id);
            $count = $cart->items()->sum('quantity');
        }
        else{
            if(session()->has('cart_id')){
                $cart = self::find(session()->get('cart_id'));
                $count = $cart->items()->sum('quantity');
            }
            else{
                if(Auth::check()){
                    if($cart = self::where('user_id',Auth::user()->id)->first()){
                        $count = $cart->items()->sum('quantity');
                    }
                }
            }
        }
        return $count;
    }

    public static function content($cart_id = null){
        $items = [];
        $cart = null;
        if($cart_id){
            $cart = self::find($cart_id);
            $items = $cart->items;
        }
        else{
            if(session()->has('cart_id')){
                $cart = self::find(session()->get('cart_id'));
                $items = $cart->items;
            }
            else{
                if(Auth::check()){
                    if($cart = self::where('user_id',Auth::user()->id)->first()){
                        $items = $cart->items;
                    }
                }
            }
        }
        if(count($items)>0){
            $items->map(function($item) use($cart){
                if(!($item->product && $item->uom)){
                    ShoppingCartItem::destroy($item->id);
                    return false;
                }
                else{
                    $item_cost = $item->uom->price;
                    if($cart->user_id){
                        $user = User::find($cart->user_id);
                        if($user->product_price_check($item->product_id)){
                            if($price = $user->uom_price_check($item->uom_id)){
                                $item_cost = (float)$price->price;
                            }
                        }
                    }
                    else{
                        if(Auth::check()){
                            $user = Auth::user();
                            if($user->product_price_check($item->product_id)){
                                if($price = $user->uom_price_check($item->uom_id)){
                                    $item_cost = (float)$price->price;
                                }
                            }
                        }
                    }

                    $item->cost = $item_cost;
                    $item->sub_total = round($item_cost*intval($item->quantity), 2, PHP_ROUND_HALF_UP);
                    $item->save();

                    return $item;
                }
            });
        }
        if($cart){
            $cart->sub_total = $cart->items()->sum('sub_total');
            $cart->save();
        }

        return $items;
    }

    public static function total($cart_id = null){
        $total = 0;
        if($cart_id){
            $cart = self::find($cart_id);
            $total = (float)$cart->sub_total;
        }
        else{
            if(session()->has('cart_id')){
                $cart = self::find(session()->get('cart_id'));
                $total = (float)$cart->sub_total;
            }
            else{
                if(Auth::check()){
                    if($cart = self::where('user_id',Auth::user()->id)->first()){
                        $total = (float)$cart->sub_total;
                    }
                }
            }
        }
        return $total;
    }
    public static function id(){
        $id = false;
        if(session()->has('cart_id')){
            $id = session()->get('cart_id');
            
        }
        else{
            if(Auth::check()){
                if($cart = self::where('user_id',Auth::user()->id)->first()){
                    $id = $cart->id;
                }
            }
        }
        return $id;
    }
}
