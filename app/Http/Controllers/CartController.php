<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use App\Product;
use App\Picture;
use App\Commands;
use App\Category;
use App\ProductAttribute;
use App\Order;
use App\UnitOfMeasure;
use App\OrderDetail;
use Auth;
use Carbon\Carbon;
use Session;
use Cart;
use App\ShoppingCartItem;
use App\Transaction;
use Hashids;
use AuthorizeNetAIM;
use Validator;
use App\ShipTo;
use Mail;

class CartController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function show(){
		$cart = Cart::content();

		return view('cart.index',compact('cart'));
	}
	public function add(Request $request){
        $product = Product::find($request->input('id'));
        $uom = UnitOfMeasure::find($request->input('uom'));

        if(Auth::check() && Auth::user()->product_price_check($product->id)){
            $total =  Auth::user()->product_price_check($product->id)->price;
        }else {
            $total = $uom->price;
        }

        Cart::add($product->id, $uom->id, $request->input('quantity'));
        if(session()->has('shipping') || !Auth::check()){
            return back()->with('success','Added to your cart successfully');
        }
        else{
            return redirect()->route('cart-select-shipping')->with('success','Added to your cart successfully');
        }
	}
	public function shipping(){
		$cart = Cart::content();
		$validator = '';
		if(!Auth::check()){
			return redirect()->route('register-or-login')->with('fail','Before you can checkout you will need to first login. If you do not have a login please register before continuing.');
		}
		else{
			$user = Auth::user();
			if(!$user->verified){
				return redirect()->back()->with('fail','Your account has not been verified. Please verify your account before proceeding with checkout.');
			}
			else{
				if($user->license_expire && $user->license_number){
					$license_expire = Carbon::createFromFormat('m/Y',$user->license_expire);
					if($license_expire->lte(Carbon::now())){
						return redirect()->back()->with('fail','Your license has expired. Please update your license information before proceeding with checkout.');
					}
				}
				else{
					return redirect()->back()->with('fail','We do not have license information for your account. Please update your information before proceeding with checkout.');
				}
			}
		}
		return view('cart.shipping',compact('cart','validator'));
	}
	public function payment(Request $request){
		$user = Auth::user();
		if($request->input('shipping_id') === 'new'){
			$shipto = new ShipTo;
			$shipto->user_id = $user->id;
			$shipto->name = $request->input('name');
			$shipto->address1 = $request->input('address1');
			$shipto->address2 = $request->input('address2');
			$shipto->city = $request->input('city');
			$shipto->state = $request->input('state');
			$shipto->zip = $request->input('zip');
			$shipto->save();
		}
		else{
			$shipto = ShipTo::find($request->input('shipping_id'));
		}
		session()->put('shipping', $shipto);

		$order = new Order;
		$order->user_id = $user->id;
		$order->shippingname = $shipto->name;
		$order->address1 = $shipto->address1;
		$order->address2 = $shipto->address2;
		$order->city = $shipto->city;
		$order->state = $shipto->state;
		$order->zip = $shipto->zip;
		$order->phone = $user->phone;
		$order->secondary_phone = $user->secondary_phone;

		$order->orderDate = Carbon::now()->toDateTimeString();
		$order->total = Cart::total();
		//$order->save();
		session(['order'=>$order]);
		session()->reflash();

		$now = Carbon::now();
		return view('cart.payment',compact('order','now'));

	}
	public function checkout(Request $request){
		$order = session('order');
		$user = Auth::user();
		$transaction = new Transaction();
		if($request->input('same_as_shipping')){
			$transaction->name = $order->shippingname;
			$transaction->address1 = $order->address1;
			$transaction->address2 = $order->address2;
			$transaction->city = $order->city;
			$transaction->state = $order->state;
			$transaction->zip = $order->zip;
		}
		else{
			$transaction->name = $request->input('name');
			$transaction->address1 = $request->input('address1');
			$transaction->address2 = $request->input('address2');
			$transaction->city = $request->input('city');
			$transaction->state = $request->input('state');
			$transaction->zip = $request->input('zip');
		}

		if($request->has('purchase_order_check')){
			if(empty($request->input('purchase_order_number'))){
				$response_text = 'Purchase order number required';
				return view('cart.payment',compact('order','response_text'));
			}
			else{
				$transaction->purchase_order_num = $request->input('purchase_order_number');
				$transaction->save();
				$order->save();
			}
		}
		else{
			$authorize = new AuthorizeNetAIM;
			$authorize->description = "World Wide Medical Distributors";
			/*
			Test Cards:
			American Express	370000000000002
			Discover			6011000000000012
			Visa 				4007000000027
			Second Visa 		4012888818888
			 */
			$authorize->card_num = $request->input('card_num');
			$authorize->exp_date = $request->input('expiry_month').'/'.$request->input('expiry_year');
			$authorize->card_code = $request->input('cvv');
			$user = Auth::user();
			if($user->company){
				$authorize->company = $user->company;
			}
			else{
				$authorize->first_name = $user->first_name;
				$authorize->last_name = $user->last_name;
			}
			$authorize->address = $transaction->address1.' '.$transaction->address2;
			$authorize->city = $transaction->city;
			$authorize->state = $transaction->state;
			$authorize->zip = $transaction->zip;

			$authorize->invoice_num = $order->id;
			$authorize->ship_to_address = $order->address1.' '.$order->address2;
			$authorize->ship_to_city = $order->city;
			$authorize->ship_to_state = $order->state;
			$authorize->ship_to_zip = $order->zip;

			$authorize->cust_id = $order->user_id;

			$authorize->amount = ($transaction->state=='FL')?$order->total+round($order->total * .065,2):$order->total;

			$response = $authorize->authorizeAndCapture();
			if($response->approved){
				$order->transactionStatus = 'Paid';
				$order->paymentDate = Carbon::now()->toDateTimeString();

				$transaction->response_code = $response->response_reason_code;
				$transaction->response = $response->response_reason_text;
				$transaction->transaction_id = $response->transaction_id;

				$transaction->save();
				$order->save();
			}
			else{
				$response_text = $response->response_reason_text;

				$now = Carbon::now();
				return view('cart.payment',compact('order','response_text', 'now'));

			}
		}

		$order->transaction_id = $transaction->id;
		$order->token = Hashids::encode($order->id);
		$order->save();

		$items = Cart::content();
		foreach ($items as $item) {
			$product = Product::find($item->product_id);
			if($product){
				$orderDetail = new OrderDetail;
				$orderDetail->product_id = $item->product_id;
				$orderDetail->quantity = $item->quantity;
				$orderDetail->subtotal = $item->sub_total;
	            $orderDetail->options = $item->uom->name;
				$order->details()->save($orderDetail);
			}
		}
		$cart = Cart::find(Cart::id());
		$cart->delete();
		$cart->items()->delete();
		if(session()->has('cart_id')) session()->forget('cart_id');
		if(session()->has('order')) session()->forget('order');

		if($user->email){
			$email = trim($user->email);

			$shippingname = $order->shippingname;
			$address1 = $order->address1;
			$address2 = $order->address2;
			$city = $order->city;
			$state = $order->state;
			$zip = $order->zip;
			$phone = $order->phone;
			$secondary_phone = $order->secondary_phone;
			$total = $order->total;
			$details = $order->details()->get();

			$data = [
				'order' => $order,
				'details' => $details,
				'transaction' => $transaction
			];
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				Mail::send("emails.receipt", $data, function($message) use($email){
				    $message->to($email)->subject('World Wide Medical Order Receipt');
				});
			}
		}
		Mail::send('emails.ordernotif',$data, function($message){
			$message
    			->to('brent@wwmdusa.com', 'Brent Weintraub')
    			->bcc('hw.pmdi@gmail.com', 'Howard Weintraub')
    			->bcc('bw.wwmd@gmail.com', 'Brent Weintraub')
    			->bcc('lbodden@drivegroupllc.com', 'Leopold Bodden')
	        	->subject('New Order - World Wide Medical');
		});

		return redirect()->route('order-show',$order->token);

	}
	public function create(Request $request)
	{
		if($request->input('category') == 'other'){
			$category = new Category;
			$category->name = $request->input('category-other');
			$category->save();
		}
		else{
			$category = Category::where('slug','=',$request->input('category'))->first();
		}

		$product = new Product;
		$product->name = $request->input('productname');
		$product->sku = $request->input('sku');
		$product->category_id = $category->id;
		$product->description = $request->input('productdescription');
		$product->inStock = $request->input('stock');
		$product->note = $request->input('note');

		if(!$product->save()){
			return redirect()->route('admin-dashboard')->withInput()->with(['failed'=>'The product was unable to be added. Please check your input and try again.','modal'=>'add-inventory']);
		}

		if($request->hasFile('image')){
			$destinationPath = public_path().'/pictures';
			$filename = $request->file('image')->getClientOriginalName();
			$request->file('image')->move($destinationPath, $filename);

			$image = new Picture;
			$image->name = $filename;
			$image->type = 'product';
			$image->key = $product->id;
			$image->save();
		}

		return redirect()->route('admin-dashboard')->with('success','Product created successfully.');
	}

	public function remove(Request $request, $rowid){
		ShoppingCartItem::destroy($rowid);
		if($request->ajax()){
			return response()->json(Cart::content());
		}
		else{
			return redirect()->route('cart')->with('success','Product successfully removed from cart.');
		}
	}

	public function update(Request $request){
		$item = ShoppingCartItem::find($request->input('rowid'));
		$item->quantity = $request->input('quantity');
		$item->save();

		if($request->ajax()){
			return response()->json(Cart::content());
		}
		else{
			return redirect()->route('cart')->with('success','Product successfully updated.');
		}
	}
	public function select_shipping(){
		return view('cart.select-shipping');
	}
	public function set_shipping(Request $request){
		$user = Auth::user();
		if($request->input('shipping_id') === 'new'){
			$shipto = new ShipTo;
			$shipto->name = $request->input('name');
			$shipto->address1 = $request->input('address1');
			$shipto->address2 = $request->input('address2');
			$shipto->city = $request->input('city');
			$shipto->state = $request->input('state');
			$shipto->zip = $request->input('zip');
			$shipto->save();
		}
		else{
			$shipto = ShipTo::find($request->input('shipping_id'));
		}
		session()->put('shipping', $shipto);

		return redirect()->route('home')->with('success','Shipping set successfully.');
	}

	public function get_cart_total(Request $request){
		return Cart::total($request->input('cart_id'));
	}


	/*
	 * Admin Cart routes
	 */
	public function admin_shipping($cart_id){
		$cart = Cart::content($cart_id);
		$shoppingcart = Cart::find($cart_id);
		$user = $shoppingcart->user;

		return view('cart.admin.shipping',compact('cart','user','cart_id'));
	}
	public function admin_payment(Request $request, $cart_id){

		$cart = Cart::content($cart_id);
		$shoppingcart = Cart::find($cart_id);
		$user = $shoppingcart->user;

		if($request->input('shipping_id') === 'new'){
			$shipto = new ShipTo;
			$shipto->user_id = $user->id;
			$shipto->name = $request->input('name');
			$shipto->address1 = $request->input('address1');
			$shipto->address2 = $request->input('address2');
			$shipto->city = $request->input('city');
			$shipto->state = $request->input('state');
			$shipto->zip = $request->input('zip');
			$shipto->save();
		}
		else{
			$shipto = ShipTo::find($request->input('shipping_id'));
		}
		session()->put('admin_shipping', $shipto);

		$order = new Order;
		$order->user_id = $user->id;
		$order->shippingname = $shipto->name;
		$order->address1 = $shipto->address1;
		$order->address2 = $shipto->address2;
		$order->city = $shipto->city;
		$order->state = $shipto->state;
		$order->zip = $shipto->zip;
		$order->phone = $user->phone;
		$order->secondary_phone = $user->secondary_phone;

		$order->orderDate = Carbon::now()->toDateTimeString();
		$order->total = Cart::total($cart_id);
		//$order->save();
		session(['admin_order'=>$order]);
		session()->reflash();

		$now = Carbon::now();
		return view('cart.admin.payment',compact('order','now','cart_id'));
	}
	public function admin_checkout(Request $request, $cart_id){

		$order = session('admin_order');
		$shoppingcart = Cart::find($cart_id);
		$user = $shoppingcart->user;

		$transaction = new Transaction();
		if($request->input('same_as_shipping')){
			$transaction->name = $order->shippingname;
			$transaction->address1 = $order->address1;
			$transaction->address2 = $order->address2;
			$transaction->city = $order->city;
			$transaction->state = $order->state;
			$transaction->zip = $order->zip;
		}
		else{
			$transaction->name = $request->input('name');
			$transaction->address1 = $request->input('address1');
			$transaction->address2 = $request->input('address2');
			$transaction->city = $request->input('city');
			$transaction->state = $request->input('state');
			$transaction->zip = $request->input('zip');
		}

		if($request->has('purchase_order_check')){
			if(empty($request->input('purchase_order_number'))){
				$response_text = 'Purchase order number required';
				return view('cart.admin.payment',compact('order','response_text'));
			}
			else{
				$transaction->purchase_order_num = $request->input('purchase_order_number');
				$transaction->save();
				$order->save();
			}
		}
		else{
			$authorize = new AuthorizeNetAIM;
			$authorize->description = "World Wide Medical Distributors";
			/*
			Test Cards:
			American Express	370000000000002
			Discover			6011000000000012
			Visa 				4007000000027
			Second Visa 		4012888818888
			 */
			$authorize->card_num = $request->input('card_num');
			$authorize->exp_date = $request->input('expiry_month').'/'.$request->input('expiry_year');
			$authorize->card_code = $request->input('cvv');

			if($user->company){
				$authorize->company = $user->company;
			}
			else{
				$authorize->first_name = $user->first_name;
				$authorize->last_name = $user->last_name;
			}
			$authorize->address = $transaction->address1.' '.$transaction->address2;
			$authorize->city = $transaction->city;
			$authorize->state = $transaction->state;
			$authorize->zip = $transaction->zip;

			$authorize->invoice_num = $order->id;
			$authorize->ship_to_address = $order->address1.' '.$order->address2;
			$authorize->ship_to_city = $order->city;
			$authorize->ship_to_state = $order->state;
			$authorize->ship_to_zip = $order->zip;

			$authorize->cust_id = $order->user_id;

			$authorize->amount = ($transaction->state=='FL')?$order->total+round($order->total * .065,2):$order->total;

			$response = $authorize->authorizeAndCapture();
			if($response->approved){
				$order->transactionStatus = 'Paid';
				$order->paymentDate = Carbon::now()->toDateTimeString();

				$transaction->response_code = $response->response_reason_code;
				$transaction->response = $response->response_reason_text;
				$transaction->transaction_id = $response->transaction_id;

				$transaction->save();
				$order->save();
			}
			else{
				$response_text = $response->response_reason_text;

				$now = Carbon::now();
				return view('cart.admin.payment',compact('order','response_text', 'now'));

			}
		}

		$order->transaction_id = $transaction->id;
		$order->token = Hashids::encode($order->id);
		$order->save();

		$items = Cart::content($cart_id);
		foreach ($items as $item) {
			$product = Product::find($item->product_id);
			if($product){
				$orderDetail = new OrderDetail;
				$orderDetail->product_id = $item->product_id;
				$orderDetail->quantity = $item->quantity;
				$orderDetail->subtotal = $item->sub_total;
	            $orderDetail->options = $item->uom->name;
				$order->details()->save($orderDetail);
			}
		}
		$cart = Cart::find($cart_id);
		$cart->delete();
		$cart->items()->delete();
		if(session()->has('cart_id')) session()->forget('cart_id');
		if(session()->has('admin_order')) session()->forget('admin_order');
		Cart::destroy($cart_id);

		if($user->email){
			$email = trim($user->email);

			$shippingname = $order->shippingname;
			$address1 = $order->address1;
			$address2 = $order->address2;
			$city = $order->city;
			$state = $order->state;
			$zip = $order->zip;
			$phone = $order->phone;
			$secondary_phone = $order->secondary_phone;
			$total = $order->total;
			$details = $order->details()->get();

			$data = [
				'order' => $order,
				'details' => $details,
				'transaction' => $transaction
			];
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				Mail::send("emails.receipt", $data, function($message) use($email){
				    $message->to($email)->subject('World Wide Medical Order Receipt');
				});
			}
		}
		Mail::send('emails.ordernotif',$data, function($message){
			$message
    			->to('brent@wwmdusa.com', 'Brent Weintraub')
    			->bcc('hw.pmdi@gmail.com', 'Howard Weintraub')
    			->bcc('bw.wwmd@gmail.com', 'Brent Weintraub')
    			->bcc('lbodden@drivegroupllc.com', 'Leopold Bodden')
	        	->subject('New Order - World Wide Medical');
		});

		return redirect()->route('order-show',$order->token);

	}
}
