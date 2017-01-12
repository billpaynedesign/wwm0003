<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\User;
use App\Product;
use App\Picture;
use App\Category;
use App\ProductAttribute;
use App\Order;
use App\OrderDetails;
use App\Commands\CategoryHelper;
use App\OptionGroup;

class DashboardController extends AdminController {
    public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
        $categories = Category::all();
        $categoryHelper = new CategoryHelper($categories);
        $products = Product::all();
        $latest_products = Product::take(6)->orderBy('created_at','DESC')->get();
        $pictures = array();
        $latest_orders = Order::orderBy('created_at','=','DESC')->take(5)->get();
        $orders = Order::orderBy('created_at','=','DESC')->get();
        $backorders = Order::with(['details' => function ($query) {
                        $query->where('backordered', '>', 0);
                    }])->get();
        $users = User::all();

        $option_groups = OptionGroup::all();        
        return view('admin.index',  compact('option_groups','products','categories','latest_products','orders', 'backorders','latest_orders','users','categoryHelper'));
	}
}