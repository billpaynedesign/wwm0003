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
class CategoryController extends Controller {

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
		$category = Category::findBySlug($slug);
		//dd($category->childProducts());
		return view('products.category',compact('category'));
	}
	public function create(Request $request)
	{
		//$parent = Category::findBySlug($request->input('parent_category'));
		$category = new Category;
		$category->name = $request->input('category_name');
		$category->parent_id = $request->has('parent_category')?intval($request->input('parent_category')):NULL;
		$category->description = $request->input('description');
		$category->active = 1;
		$category->featured = 0;

		if($request->hasFile('image')){
			$destinationPath = public_path().'/pictures';
			$filename = $request->file('image')->getClientOriginalName();
			$request->file('image')->move($destinationPath, $filename);

			$category->picture = $filename;
		}

		$category->save();

		return redirect()->route('admin-categories')->with(['tab'=>'categories','success'=>'Category created successfully.']);
	}

	public function delete(Request $request){
		$category = Category::destroy($request->input('id'));
		if(!Category::find($request->input('id'))){
			return response()->json(['response' => 'success', 'id' => $request->input('id')]);
		}
		else{
			return response()->json(['response' => 'fail']);
		}
	}

	public function toggleFeatured(Request $request){
		$category = Category::find($request->input('id'));
		$category->featured = $category->featured?0:1;
		$category->save();

		return response()->json(['response' => $category->featured, 'id' => $request->input('id')]);
	}
	public function toggleActive(Request $request){
		$category = Category::find($request->input('id'));
		$category->active = $category->active?0:1;
		$category->save();

		return response()->json(['response' => $category->active, 'id' => $request->input('id')]);
	}
}
