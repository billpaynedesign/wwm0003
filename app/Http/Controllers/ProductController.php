<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use App\Product;
use App\Picture;
use App\Commands;
use App\Category;
use App\UnitOfMeasure;
use App\Review;
use App\Vendor;
use Session;
use DB;
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
		if($product){
			return view('products.product',compact('product'));
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
        //$categoryHelper = new CategoryHelper($categories);
		return view('products.edit',compact('product','categories'));
	}
	public function update(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-products')->with('tab','products');
		}else{
			$this->validate($request,[
				'name' => 'required|string',
				'manufacturer' => 'string',
				'item_number' => 'string',
				'short_description' => 'string',
				'description' => 'string',
				'note' => 'string',
				'category' => 'required'
			]);

			$product = Product::find($request->input('id'));
			$product->fill([
				/* moved to unit of measure
				'msrp' => $request->input('msrp'),
				'price' => $request->input('price'),
				*/
				'name' => $request->input('name'),
				'manufacturer' => $request->input('manufacturer'),
				'item_number' => $request->input('item_number'),
				'short_description' => $request->input('productshortdescription'),
				'description' => $request->input('productdescription'),
				'note' => $request->input('note'),

				'has_lot_expiry' => $request->has('lot_expiry_check'),
				'require_license' => $request->has('require_license'),
				'active' => $request->has('active'),
				'taxable' => $request->has('taxable')
			]);

			if($request->hasFile('image')){
				$destinationPath = public_path().'/pictures';
				$filename = $product->slug.'.'.$request->file('image')->getClientOriginalExtension();
				$request->file('image')->move($destinationPath, $filename);
				$product->picture = $filename;
			}

			$product->save();

			$product->categories()->sync($request->input('category'));
			if($request->has('vendors')) $product->vendors()->sync($request->input('vendors'));


			if($request->has('uom')){
				foreach($request->input('uom') as $uom_id => $name){
					$uom = UnitOfMeasure::find($uom_id);
					$uom->name = $name;
					$uom->price = array_key_exists($uom_id,$request->input('price'))?$request->input('price')[$uom_id]:NULL;
					$uom->msrp = array_key_exists($uom_id,$request->input('msrp'))?$request->input('msrp')[$uom_id]:NULL;
                    $weight = array_key_exists($uom_id,$request->input('weight'))?$request->input('weight')[$uom_id]:NULL;
                    if($weight){
                        $weight_unit = array_key_exists($uom_id,$request->input('weight_unit'))?$request->input('weight_unit')[$uom_id]:NULL;
                        if($weight_unit === 'oz'){
                            $weight = $weight/16;
                        }

                        $uom->weight = $weight;
                    }
					$uom->save();
				}
			}
			if($request->has('uom_new')){
				foreach($request->input('uom_new') as $key => $name){
					$uom = new UnitOfMeasure;
					$uom->name = $name;
					$uom->product_id = $product->id;
					$uom->price = array_key_exists($key,$request->input('price_new'))?$request->input('price_new')[$key]:NULL;
					$uom->msrp = array_key_exists($key,$request->input('msrp_new'))?$request->input('msrp_new')[$key]:NULL;
                    $weight = array_key_exists($key,$request->input('weight_new'))?$request->input('weight_new')[$key]:NULL;
                    if($weight){
                        $weight_unit = array_key_exists($key,$request->input('weight_unit_new'))?$request->input('weight_unit_new')[$key]:NULL;
                        if($weight_unit === 'oz'){
                            $weight = $weight/16;
                        }

                        $uom->weight = $weight;
                    }
					$uom->save();
				}
			}

			return redirect()->route('product-edit', $product->id)->with(['tab'=>'products','success'=>'Product updated successfully.']);
		}
	}
	public function create(Request $request)
	{
		$this->validate($request,[
			'name' => 'required|string',
			'manufacturer' => 'string',
			'item_number' => 'string',
			'short_description' => 'string',
			'description' => 'string',
			'note' => 'string'
		]);

		$product = new Product();
		$product->fill([
			/* moved to unit of measure
			'msrp' => $request->input('msrp'),
			'price' => $request->input('price'),
			*/
			'name' => $request->input('productname'),
			'manufacturer' => $request->input('manufacturer'),
			'item_number' => $request->input('item_number'),
			'short_description' => $request->input('productshortdescription'),
			'description' => $request->input('productdescription'),

			'has_lot_expiry' => $request->has('lot_expiry_check'),
			'require_license' => $request->has('require_license'),
			'note' => $request->input('note'),
			'active' => 1
		]);

		if($request->hasFile('image')){
			$destinationPath = public_path().'/pictures';
			$filename = $product->slug.'.'.$request->file('image')->getClientOriginalExtension();
			$request->file('image')->move($destinationPath, $filename);
			$product->picture = $filename;
		}

		$product->save();

		$product->categories()->sync($request->input('category'));
		if($request->has('vendors')) $product->vendors()->sync($request->input('vendors'));

		foreach($request->input('uom') as $key => $name){
			$uom = new UnitOfMeasure;
			$uom->name = $name;
			$uom->product_id = $product->id;
			$uom->price = array_key_exists($key,$request->input('price'))?$request->input('price')[$key]:NULL;
			$uom->msrp = array_key_exists($key,$request->input('msrp'))?$request->input('msrp')[$key]:NULL;
            $weight = array_key_exists($key,$request->input('weight'))?$request->input('weight')[$key]:NULL;
            if($weight){
                $weight_unit = array_key_exists($key,$request->input('weight_unit'))?$request->input('weight_unit')[$key]:NULL;
                if($weight_unit === 'oz'){
                    $weight = $weight/16;
                }

                $uom->weight = $weight;
            }
			$uom->save();
		}

		return redirect()->route('admin-products')->with(['tab'=>'products','success'=>'Product created successfully.']);
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
		return redirect()->route('admin-products')->with('success','Import successfully uploaded.');
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

	public function barcodes(Request $request){
        if($request->has('uoms')){
            $uoms = UnitOfMeasure::has('products')->with('products')->whereIn('product_id',$request->input('uoms'))->get()->sortBy('products.name');
            return view('products.barcodes',compact('uoms'));
        }
        else{
			$uoms = UnitOfMeasure::has('products')->with('products')->get()->sortBy('products.name');
			return view('products.barcodes',compact('uoms'));
		}
	}

	public function vendor_pricing_edit($id){
		$product = Product::findOrFail($id);
		$vendors = $product->vendors()->orderBy('name','asc')->get();
		$units_of_measure = $product->units_of_measure()->orderBy('name','asc')->get();
		return view('products.vendor-pricing-edit',compact('product','vendors','units_of_measure'));
	}
	public function vendor_pricing_update(Request $request,$id){
		$product = Product::findOrFail($id);
		$message_bag = null;
		if($request->has('costs')){
			$costs = $request->input('costs');
			foreach($costs as $vendor_id => $cost){
				if($vendor = Vendor::find($vendor_id)){
					foreach($cost as $uom_id => $value){
						if($vendor = UnitOfMeasure::find($uom_id)){
							$uom_query = DB::table('uom_vendor')->where('uom_id',$uom_id)->where('vendor_id',$vendor_id);
							if($uom_query->exists()){
								try {
								    // Get the updated rows count here. Keep in mind that zero is a
								    // valid value (not failure) if there were no updates needed
								    $count = $uom_query->update([
										'cost' => (float)$value
									]);
								} catch (\Illuminate\Database\QueryException $e) {
									$message_bag->add('error', "Error updating {$vendor->name} {$uom->name} cost");
								}
							}
							else{
								try {
								    // Get the updated rows count here. Keep in mind that zero is a
								    // valid value (not failure) if there were no updates needed
								    $count = DB::table('uom_vendor')->insert([
											'uom_id' => $uom_id,
											'vendor_id' => $vendor_id,
											'cost' => (float)$value
										]);
								} catch (\Illuminate\Database\QueryException $e) {
									$message_bag->add('error', "Error creating {$vendor->name} {$uom->name} cost. {$e->message}");
								}
							}
						}
					}
				}
			}
		}
		if(isset($message_bag)){
			return redirect()->route('product-vendor-pricing-edit',$id)->withErrors($message_bag)->with('fail','An error occured while saving, please try again');
		}
		else{
			return redirect()->route('product-vendor-pricing-edit',$id)->with('success','Product vendor pricing updated successfully');
		}
	}
}
