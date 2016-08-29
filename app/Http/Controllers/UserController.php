<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use Auth;
use App\UserPricing;
use App\Product;

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
	public function info($id){
		$user = User::find($id);
		return view('user.info',compact('user'));
	}
	public function update(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-dashboard')->with('tab','users');
		}else{
			$user = User::find($request->input('id'));
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

	public function product($id){
		$user = User::find($id);
		return view('user.product',compact('user'));
	}
	public function product_submit(Request $request, $id){
		//dd($request->all());
		if($request->has('cancel')){
			return redirect()->route('admin-dashboard')->with('tab','users');
		}else{
			foreach ($request->input('prices') as $userpricing_id => $price) {
				$userpricing = UserPricing::find($userpricing_id);
				$userpricing->price = $price;
				$userpricing->save();
			}
			if($request->has('delete')){
				foreach ($request->input('delete') as $delete_id) {
					UserPricing::destroy($delete_id);
				}
			}
			return redirect()->route('user-product',$id)->with('success','Updated successfully!');
		}
	}
	public function product_add(Request $request, $id){
		//dd($request->all());
		$product = Product::find($request->input('product_id'));
		$userpricing = new UserPricing;
		$userpricing->product_id = $product->id;
		$userpricing->user_id = $id;
		$userpricing->price = $product->price;
		$userpricing->save();
		return redirect()->route('user-product',$id)->with('success','Product added successfully!');
	}
}
