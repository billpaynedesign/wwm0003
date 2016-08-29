<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use App\Product;
use App\Picture;
use App\Commands;
use App\Category;
use App\ProductAttribute;
use App\Review;
use Session;
use App\Commands\CategoryHelper;

class ProductController extends Controller {

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
	public function show($slug){
		$product = Product::findBySlug($slug);
		$attributesAll = ProductAttribute::all();
		$attributesNames = ProductAttribute::groupBy('name')->get();
		if($product->active){
			return view('products.product',compact('product', 'attributesAll','attributesNames'));
		}
		else{
			return back();
		}
	}
	public function index(){
		$products = Product::active()->get();
		return view('products.all',compact('products'));
	}
	public function sale(){
		$products = Product::active()->where('discountAvailable','=','1')->get();
		return view('products.sale',compact('products'));
	}
	public function latest(){
		$latest_products = Product::active()->take(6)->orderBy('created_at','DESC')->get();
		return view('products.latest',compact('latest_products'));
	}
	public function edit($id){
		$product = Product::find($id);
        $categories = Category::all();
        $categoryHelper = new CategoryHelper($categories);
		return view('products.edit',compact('product','categoryHelper'));
	}
	public function update(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-dashboard')->with('tab','products');
		}else{
			$product = Product::find($request->input('id'));
			$product->name = $request->input('name');
			$product->category_id = $request->input('category');
			$product->price = (!empty($request->input('price')))?$request->input('price'):0.00;
			$product->msrp = (!empty($request->input('msrp')))?$request->input('msrp'):0.00;
			$product->item_number = $request->input('item_number');
			$product->description = $request->input('productdescription');
			$product->short_description = $request->input('short_description');
			$product->manufacturer = $request->input('manufacturer');
			$product->active = $request->has('active')?1:0;
			$product->has_lot_expiry = $request->has('has_lot_expiry')?1:0;
			$product->require_license = $request->has('require_license')?1:0;
			

			$product->note = $request->input('note');
			if($request->hasFile('image')){
				$destinationPath = public_path().'/pictures';
				$filename = $request->file('image')->getClientOriginalName();
				$request->file('image')->move($destinationPath, $filename);
				$product->picture = $filename;
			}
			 if($request->has('attributes')){
			 	foreach ($request->input('attributes') as $attribute_id) {
			 		$attribute = ProductAttribute::destroy($attribute_id);
				}
			 }
			$product->save();
			return redirect()->route('admin-dashboard')->with(['tab'=>'products','success'=>'Product updated successfully.']);
		}
	}
	public function create(Request $request)
	{
			$product = new Product;
			$product->name = $request->input('productname');
			$product->category_id = $request->input('category');
			$product->msrp = $request->input('msrp');
			$product->price = $request->input('price');
			$product->manufacturer = $request->input('manufacturer');
			$product->item_number = $request->input('item_number');
			$product->short_description = $request->input('productshortdescription');
			$product->description = $request->input('productdescription');
			$product->has_lot_expiry = $request->has('lot_expiry_check');
			$product->require_license = $request->has('require_license')?1:0;
			$product->note = $request->input('note');
			$product->active = 1;

			$product->save();

			if($request->hasFile('image')){
				$destinationPath = public_path().'/pictures';
				$filename = $product->slug.'.'.$request->file('image')->getClientOriginalExtension();
				$request->file('image')->move($destinationPath, $filename);
				$product->picture = $filename;
			}
			$product->save();

		return redirect()->route('admin-dashboard')->with(['tab'=>'products','success'=>'Product created successfully.']);
	}

	public function postDelete(Request $request){
		$product = Product::destroy($request->input('id'));
		if(!Product::find($request->input('id'))){
			return response()->json(['response' => 'success', 'id' => $request->input('id')]);
		}
	}
	public function getDelete($id){
		$product = Product::destroy($id);
		if(!Product::find($id)){
			return back()->with(['success'=>'Product deleted successfully.','tab'=>'products']);
		}
		else{
			return back()->with(['fail'=>'We are unable to delete the requested product at this time.','tab'=>'products']);
		}
	}
	public function import(){

	}
	public function import_preview(Request $request){
		/** @var String The mimetype of the file uploaded */
		$mimeType = $request->file('csv')->getMimeType();
		/** @var Array array of accepted mime types for uploaded file */
		$supportedTypes = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		//check if the mimetype sent is a supported mimetype
    	if(!in_array($mimeType, $supportedTypes)){
    		//if not supprted redirect with error message and input
    		return back()->with('fail','Your are trying to upload an unsupported file type.');
		}
		else{
			$excel = \App::make('excel');
			$csv_obj = $excel->load($request->file('csv'));
			Session::put('csv',$csv_obj->toArray());
			$csv = $csv_obj->toArray();
			//store the array in the current session for use
			//show admin/csvpreview and share csv_array, brand, filename with the view
			return view('admin.csvpreview', compact('csv'));

		}
	}
	public function import_upload(Request $request){
		$columns = $request->input('columns');
		//get the full csv array from session set in import_preview()
		$csv = Session::get('csv');
		//cycle again through the csv array to update the database
		foreach ($csv as $row) {
			$k = 0;
			//cycle through each column in the row to match the column in the database
			foreach ($row as $key => $value) {
				if($columns[$k] == 'sku'){  
					$product = Product::where('sku','=',$value)->first();
					if(is_null($product)){
						$product = new Product;
					}
					break;
				}
			}
			$i = 0;
			/**
			 * example:
			 * based on what user input
			 * $column[1] = code;
			 * $egift->$column[1] = $value; is the same as $egift->code = $value;
			 * this lets the user match the column of data in the csv to a column in the database
			 */
			foreach ($row as $key => $value) {
				//if the column was set to do not include... don't include it in the database
				if($columns[$i] != 'do_not_include' && $columns[$i] != 'category'){
					if($columns[$i]=='price'||$columns[$i]=='msrp'){
						$value = preg_replace('/[\$,]/', '', $value);
						$product->$columns[$i] = floatval($value);
					}
					else{
						$product->$columns[$i] = $value;
					}
				}
				else if($columns[$i] == 'category'){
					$category = Category::findBySlug(str_slug($value));
					if(is_null($category)){
						$category = Category::find(1);
					}
					$product->category()->associate($category);
				}
				$i++;
			}
			$product->save();
		}
		//get rid of the csv array in the session
		Session::forget('csv');
		//redirect back to merchant management home with success message
		return redirect()->route('admin-dashboard')->with('success','Import successfully uploaded.');
	}
	public function attribute_create($id){
		$product = Product::find($id);
		//DB::setFetchMode(PDO::FETCH_ASSOC);
		$attributes = ProductAttribute::where('product_id', '=', $id)->orderBy('id')->get();
		$attributeNames = ProductAttribute::groupby('name')->get();
		$attributesAll = ProductAttribute::orderBy('id')->get();
		$attributesAllGrouped = $attributesAll->groupBy('name');
		//$attributesAllGrouped = $attributesAll->groupBy('name')->toArray();
		//dd($attributesAllGrouped->toArray());
		//DB::setFetchMode(PDO::FETCH_CLASS);

		//dd($attributes1, $attributes2, $attributes3);
		return view('products.attribute',compact('product', 'attributes', 'attributeNames', 'attributesAll', 'attributesAllGrouped'));
	}
	public function attribute_new(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-dashboard')->with('tab','products');
		}
		else{
			$product_id = $request->input('product_id');
			$product = Product::find($product_id);
			$allattributes = ProductAttribute::where('product_id', '=', $product_id)->orderBy('id')->get();
			$name = $request->input('name');
			$i = 0;
			
			//dd($request->input('options_active'));
			foreach($request->input('options') as $option){
				//$attribute = $product->productAttributes()->where('name','=',$request->input('attribute_name')[$i])->where('option','=', $request->input('options')[$i])->first();
				$attribute = ProductAttribute::where('product_id', '=', $product_id)->where('name','=',$request->input('name'))->where('option','=', $request->input('options')[$i])->first();
				
				if(is_null($attribute)){ 
					$attribute = new ProductAttribute;
				}
				
				$attribute->product_id = $product_id;
				$attribute->name = $name;
				//$attribute->active = $request->input('options_active');
				$attribute->option = $request->input('options')[$i];
				$attribute->price = $request->input('options_prices')[$i];
				$attribute->msrp = $request->input('options_msrp')[$i];
				//$attribute->active = 1;
				//dd($attribute);
								$attribute->active = $request->input('options_active')[$i] == 'on'?1:0;

				$attribute->save();
				
				$i++;
			}
			//dd($request->input('options_active'));
			return redirect()->route('admin-dashboard')->with(['success'=>'Attributes successfully added.','tab'=>'products']);
		}
	}
	public function attribute_edit($id){
		$attribute = ProductAttribute::find($id);
		return view('products.attributeedit',compact('attribute'));
	}
	public function attribute_update(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-dashboard')->with('tab','products');
		}
		else{
			$attribute = ProductAttribute::find($request->input('id'));
			$attribute->option = $request->input('option');
			$attribute->price = $request->input('price');
			$attribute->discount = $request->input('discount');
			$attribute->msrp = $request->input('msrp');
			$attribute->inStock = $request->input('inStock');
			$attribute->featured = $request->input('featured')?true:false;
			$attribute->available = $request->input('available')?true:false;
			$attribute->discountAvailable = $request->input('discountAvailable')?true:false;
			$attribute->save();

			return redirect()->route('admin-dashboard')->with(['success'=>'Attributes successfully updated.','tab'=>'products']);
		}
	}
	public function attribute_delete(Request $request){
		$attribute = ProductAttribute::destroy($request->input('id'));
		if(!ProductAttribute::find($request->input('id'))){
			return response()->json(['response' => 'success', 'id' => $request->input('id')]);
		}
	}
	public function attribute_toggle(Request $request)
	{
		$attribute = ProductAttribute::find($request->input('id'));
		$attribute->active = $attribute->active?1:0;

		//foreach($request->input(''))
		return response()->json(['response' => $attribute->active, 'id' => $request->input('id')]);
	}
	public function toggleActive(Request $request){
		$product = Product::find($request->input('id'));
		$product->active = $product->active?0:1;
		$product->save();

		return response()->json(['response' => $product->active, 'id' => $request->input('id')]);
	}

	public function toggleFeatured(Request $request){
		$product = Product::find($request->input('id'));
		$product->featured = $product->featured?0:1;
		$product->save();

		return response()->json(['response' => $product->featured, 'id' => $request->input('id')]);
	}
	public function infoModal(Request $request){
		$product = Product::find(intval($request->input('id')));
		return view('products.product-modal',compact('product'));
	}
	public function addReview(Request $request){
		$review = new Review;
		$review->product_id = intval($request->input('id'));
		$review->name =  $request->input('nickname');
		$review->review_text = $request->input('review_text');
		$review->stars = $request->input('stars');
		$review->save();

		return back()->with(['success'=>'Thank you for your review!']);

	}
}
