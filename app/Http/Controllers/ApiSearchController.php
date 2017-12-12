<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use App\Product;
use App\Picture;
use App\Commands;
use App\Category;
use App\ProductAttribute;
use Session;
class ApiSearchController  extends Controller {

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

	public function index(Request $request){

		// Retrieve the user's input and escape it
		$query = e($request->input('q',''));

		// If the input is empty, return an error response
		if(!$query && $query == '') return response()->json(array(), 400);

		$products = Product::active()->where('name','like','%'.$query.'%')->orderBy('name','asc')->take(50)->get(['slug','name','picture','item_number'])->toArray();
		$productsByNumber = Product::active()->where('item_number','=',strtoupper($query))->orderBy('name','asc')->take(50)->get(['slug','name','picture','item_number'])->toArray();
		$productsByNumber = array_merge(Product::active()->where('item_number','=',strtolower($query))->orderBy('name','asc')->take(50)->get(['slug','name','picture','item_number'])->toArray(), $productsByNumber);
		$categories = Category::active()->where('name','like','%'.$query.'%')->orderBy('name','asc')->take(50)->get(['slug','name','picture'])->toArray();

		// Normalize data
		$products = $this->appendURL($products, 'product');
		$productsByNumber = $this->appendURL($productsByNumber, 'product');
		$categories = $this->appendURL($categories, 'category');
		// Add type of data to each item of each set of results
		$products = $this->appendValue($products, 'product', 'class');
		$productsByNumber = $this->appendValue($productsByNumber, 'item_number', 'class');
		$categories = $this->appendValue($categories, 'category', 'class');

		//replace name with the item number to normalize the api call
		foreach ($productsByNumber as $k => $v) {
			$productsByNumber[$k]['name'] = $v['item_number'];
		}
		// Merge all data into one array
		$data = array_merge($products, $productsByNumber, $categories);

		return response()->json(array(
			'data'=>$data
		));
	}
	public function appendValue($data, $type, $element)
	{
		// operate on the item passed by reference, adding the element and type
		foreach ($data as $key => & $item) {
			$item[$element] = $type;
		}
		return $data;
	}

	public function appendURL($data, $prefix)
	{
		// operate on the item passed by reference, adding the url based on slug
		foreach ($data as $key => & $item) {
			$item['url'] = url($prefix.'/'.$item['slug']);
		}
		return $data;
	}
	public function addProduct(Request $request){

		// Retrieve the user's input and escape it
		$query = e($request->input('q',''));

		// If the input is empty, return an error response
		if(!$query && $query == '') return response()->json(array(), 400);

		$products = Product::active()->where('name','like','%'.$query.'%')->orderBy('name','asc')->take(50)->get(['id','slug','name','picture','item_number'])->toArray();
		$productsByNumber = Product::active()->where('item_number','=',strtoupper($query))->orderBy('name','asc')->take(50)->get(['id','slug','name','picture','item_number'])->toArray();
		$productsByNumber = array_merge(Product::active()->where('item_number','=',strtolower($query))->orderBy('name','asc')->take(50)->get(['id','slug','name','picture','item_number'])->toArray(), $productsByNumber);

		// Normalize data
		$products = $this->appendID($products, 'product');
		$productsByNumber = $this->appendID($productsByNumber, 'product');
		// Add type of data to each item of each set of results
		$products = $this->appendValue($products, 'product', 'class');
		$productsByNumber = $this->appendValue($productsByNumber, 'item_number', 'class');

		foreach ($productsByNumber as $k => $v) {
			$productsByNumber[$k]['name'] = $v['item_number'];
		}
		// Merge all data into one array
		$data = array_merge($products, $productsByNumber);

		return response()->json(array(
			'data'=>$data
		));
	}
	public function appendID($data, $prefix)
	{
		// operate on the item passed by reference, adding the url based on slug
		foreach ($data as $key => & $item) {
			$item['url'] = $item['id'];
		}
		return $data;
	}

    /**
     * @method addUom
     *
     * Used by the selectize plugin to query products in the DB. This method
     * returns all UOM's with an active product.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function addUom(Request $request) {
        // Retrieve the user's input and escape it
        $query = e($request->input('q',''));

        // If the input is empty, return an error response
        if(!$query && $query == '') return response()->json(array(), 400);

        $products = UnitOfMeasure::whereHas('product', function($q)use($query) {
                        $q->where('active', 1);
                        $q->where('name','like','%'.$query.'%');
                        $q->orWhere('item_number','like','%'.$query.'%');
                        $q->orderBy('name','asc');
                    })->with('product')->get();

        $products = $this->appendProductName($products);

        return response()->json(['data' => $products], 200);
    }

    /**
     * @method appendProductName
     *
     * Used by addUom() to get the related products name and attach it to the UOM array as a top level property.
     * This is so the selectize plugin is able to access the property.
     *
     * @param Collection $uoms - the UOM's with attached products
     *
     * @return Collection - the updated collection
     */
    private function appendProductName($uoms) {
        return $uoms->map(function($item, $key) {
                    $item['product_name'] = $item['product']['name'];
                    return $item;
                });
    }

	public function getUomProductOptionsHtml(Request $request){
		$product = Product::find($request->input('product_id'));
		$html = '<option value="">-- Select a UOM --</option>';
		foreach ($product->units_of_measure as $uom) {
			$html .= '<option value="'.$uom->id.'">'.$uom->name.'</option>';
		}
		return $html;
	}

	public function getCartCount(Request $request){
		return response()->json(Cart::count());
	}
	public function getUsers(Request $request){
		return response()->json(User::orderBy('first_name')->get());
	}

}
