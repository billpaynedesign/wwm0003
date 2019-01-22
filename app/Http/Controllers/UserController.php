<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use Auth;
use App\UserPricing;
use App\Product;
use Cart;
use App\ShoppingCartItem;
use App\UnitOfMeasure;
use Yajra\Datatables\Facades\Datatables;

class UserController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	public function show($slug){
		$product = Product::findBySlug($slug);
		//dd($product);
		return view('products.product',compact('product'));
	}
	public function index(){
		$products = Product::all();
		return view('products.all',compact('products'));
	}
	public function edit($id){
		$user = User::find($id);
		return view('user.edit',compact('user'));
	}
	public function update(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-dashboard')->with('tab','users');
		}else{
			$user = User::findOrFail($request->input('id'));
			$user->email = $request->input('email');
			if(!empty($request->input('password'))){
				$user->password = \Hash::make($request->input('password'));
			}
			$user->first_name = $request->input('first_name');
			$user->last_name = $request->input('last_name');
			$user->phone = $request->input('phone');
			$user->secondary_phone = $request->input('secondary_phone');

			$user->admin = $request->has('admin')?true:false;
			$user->verified = $request->has('verified')?true:false;

			$user->account = $request->input('account');
			$user->company = $request->input('company');
			$user->license_number = $request->input('license_number');
			$user->license_expire = $request->input('license_expire');

			$user->no_pricing = $request->has('no_pricing');
			$user->tax_exempt = $request->has('tax_exempt');
			$user->tax_rate_id = $request->input('tax_rate_id');
            $user->gsa = $request->has('gsa');

			$user->save();

			return redirect()->route('user-edit',$user->id)->with(['success'=>'User updated successfully.']);
		}
	}
	public function profile(){
		$user = Auth::user();
		return view('user.profile',compact('user'));
	}
	public function profile_update(Request $request){
		$user = Auth::user();
		$user->company = $request->input('company');
		$user->email = $request->input('email');
		$user->password = \Hash::make($request->input('password'));
		$user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->phone = $request->input('phone');
		$user->secondary_phone = $request->input('secondary_phone');
		$user->save();

		return redirect()->route('user-profile')->with(['success'=>'Profile information updated successfully.']);
	}
	public function delete($id){
		$user = User::destroy($id);
		return redirect()->route('admin-dashboard')->with(['success'=>'User deleted successfully.','tab'=>'users']);
	}

	public function info($id){
		$user = User::find($id);
		$cart_id = null;
		$total = 0;
		$cart = [];
		if($user->cart){
			$cart_id = $user->cart->id;
			$cart = $user->cart->items;
		}
		if($cart_id) $total = Cart::total($cart_id);
		return view('user.info',compact('user', 'cart', 'cart_id', 'total'));
	}
	public function info_api($id){
		if(request()->ajax()){
			// @foreach( as $product)
			// 	<tr>
			// 		<td>{{ $product->item_number }}</td>
			// 		<td>{{ $product->name }}</td>
			// 		<td>{{ $product->manufacturer }}</td>
			// 		<td>{{ $product->price_string }}</td>
			// 		<td>{{ $product->msrp_string }}</td>
			// 		<td>{{ $user->frequent_products['quantities'][$product->id] }}</td>
			// 		<td>
			// 			<a href="javascript:void(0);" class="btn btn-link" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<img src='{{ asset('/pictures/'.$product->picture) }}' class='img-responsive center-block' />">
			// 				<img src='{{ asset('/pictures/'.$product->picture) }}' class='img-responsive center-block'  style="max-height:40px;"/>
			// 			</a>
			// 		</td>
			// 	</tr>
			// @endforeach
			$user = User::findOrFail($id);
			$frequent_products = $user->frequent_products;
			$products = collect($frequent_products['products']);
			$products = $products->map(function ($product) use($frequent_products) {
				$product->total_purchased = $frequent_products['quantities'][$product->id];
				return $product;
			});

			return Datatables::collection($products)
								->addColumn('price_string',function($product){
									return $product->price_string;
								})
								->addColumn('msrp_string',function($product){
									return $product->msrp_string;
								})
								->make(true);
		}
		abort(404);
	}
	public function product($id){
		$user = User::find($id);
		return view('user.product',compact('user'));
	}

    /**
     * @method product_submit
     *
     * Submits a product to be added to the user pricing table, deleted from the pricing table, or added to favorites list.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
	public function product_submit(Request $request, $id){
		if($request->has('cancel')){
			return redirect()->route('admin-users')->with('tab','users');
		}else{
            $user = User::find($id);
			$skus = $request->input('skus');
			foreach ($request->input('prices') as $userpricing_id => $price) {
				$userpricing = UserPricing::find($userpricing_id);
				$userpricing->price = $price;
				$userpricing->custom_sku = $skus[$userpricing_id];
				$userpricing->save();
			}
			if($request->has('delete')){
				foreach ($request->input('delete') as $delete_id) {
					UserPricing::destroy($delete_id);
				}
			}
			if($request->has('favorite')) {
                foreach ($request->input('favorite') as $product_uom_pricing_id) {
                    $ids = explode('-', $product_uom_pricing_id);
                    $list = $user->favorites_lists()->where( 'name', 'Favorites' )->first();
                    if ( !$list ) {
                        $list = $user->favorites_lists()->create( [ 'name' => 'Favorites' ] );
                    }
                    $item = new FavoritesItem( [ 'product_id' => $ids[0], 'uom_id' => $ids[1] ] );
                    $pricing = UserPricing::find($ids[2]);
                    $list->favorites_items()->save( $item );
                    $pricing->is_favorite = 1;
                    $pricing->save();
                }
            }
			return redirect()->route('user-product',$id)->with('success','Updated successfully!');
		}
	}
	public function product_add(Request $request, $id){
		//dd($request->all());
		$product = Product::find($request->input('product_id'));
		if($request->has('uom_id') && $uom = UnitOfMeasure::find($request->input('uom_id'))){
	            $userpricing = new UserPricing();
	            $userpricing->product_id = $product->id;
	            $userpricing->user_id = $id;
	            $userpricing->uom_id = $uom->id;
	            $userpricing->price = $uom->price;
	            $userpricing->save();
		}
		else{
			foreach($product->units_of_measure()->get() as $uom) {
	            $userpricing = new UserPricing();
	            $userpricing->product_id = $product->id;
	            $userpricing->user_id = $id;
	            $userpricing->uom_id = $uom->id;
	            $userpricing->price = $uom->price;
	            $userpricing->save();
	        }
		}

		return redirect()->route('user-product',$id)->with('success','Product added successfully!');
	}
    public function favorites_update(Request $request,$id) {
	    $productId = $request->input('product_id');
	    $listId = $request->input('list_id');
        if($request->has('uom') && !empty($request->input('uom'))){
	       $uom = $request->input('uom');
        }
        else{
            $uom = UnitOfMeasure::where('product_id',$productId)->first();
            $uom = $uom?$uom->id:false;
        }
        $product = Product::find($productId);
	    $user = User::find($id);
        if($product && $uom && $user){


		    $list = $user->favorites_lists()->where( 'id', $listId )->first();
		    if ( ! $list ) {
			    $list = $user->favorites_lists()->create( [ 'name' => 'Favorites' ] );
		    }

		    $item = new FavoritesItem( [ 'product_id' => $productId, 'uom_id' => $uom ] );
		    $list->favorites_items()->save( $item );

		    return redirect()->route('user-info',['id'=>$id, 'tab'=>'favorites'])->with( 'success', 'Added to favorites list successfully' );
		}
        else{
            return redirect()->route('user-info',['id'=>$id, 'tab'=>'favorites'])->with( 'false', 'Could not add to your favorites list at this time please try again' );
        }
    }

    public function new_cart(Request $request){
    	$cart = new Cart;
    	$cart->user_id = $request->input('user_id');
    	$cart->sub_total = 0;
    	$cart->save();
        return response()->json(['new_id'=>$cart->id, 'token'=>csrf_token()]);
    }

    public function add_product_cart(Request $request){
		Cart::add($request->input('product_id'), $request->input('uom_id'), $request->input('quantity'), $request->input('cart_id'));
		$cart = Cart::content($request->input('cart_id'));
		$html = view('user.part.cart_content', compact('cart'))->render();
    	return response()->json(['html'=>$html, 'token'=>csrf_token()]);
    }
    public function update_product_cart(Request $request){
    	$item = ShoppingCartItem::find($request->input('item_id'));
    	$item->quantity = $request->input('quantity');
    	$item->save();
		$cart = Cart::content($item->shopping_cart_id);
		$html = view('user.part.cart_content', compact('cart'))->render();
    	return response()->json(['html'=>$html, 'token'=>csrf_token()]);
    }
    public function remove_product_cart(Request $request){
		$item = ShoppingCartItem::find($request->input('item_id'));
		$cart_id = $item->shopping_cart_id;
		$item->delete();
		$cart = Cart::content($cart_id);
		$html = view('user.part.cart_content', compact('cart'))->render();
    	return response()->json(['html'=>$html, 'token'=>csrf_token()]);
    }

    public function barcodes(Request $request,$id){
        $frequent_products = User::find($id)->frequent_products;
        $quantities = $frequent_products['quantities'];
        $products = $frequent_products['products']->pluck('id')->toArray();
        $uoms = UnitOfMeasure::has('products')->with('products')->whereIn('product_id',$products)->get()->sortBy('products.name');
        return view('user.barcodes',compact('uoms','quantities'));
    }
}
