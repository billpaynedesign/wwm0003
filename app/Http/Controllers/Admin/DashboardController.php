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
use App\Special;

class DashboardController extends AdminController {
    public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
        $latest_products = Product::take(6)->orderBy('created_at','DESC')->get();
        $latest_orders = Order::orderBy('created_at','=','DESC')->take(5)->get();

        //$option_groups = OptionGroup::all();        
        return view('admin.index',  compact('latest_products','latest_orders'));
	}
    public function category_index(){
        $categories = Category::all();
        $categoryHelper = new CategoryHelper($categories);

        return view('admin.index-categories', compact('categories','categoryHelper'));
    }
    public function product_index(){
        $categories = Category::all();
        $categoryHelper = new CategoryHelper($categories);
        $products = Product::all();
        return view('admin.index-products', compact('products','categoryHelper'));
    }
    public function order_index(){
        $orders = Order::orderBy('created_at','=','DESC')->get();
        return view('admin.index-orders',compact('orders'));
    }
    public function backorder_index(){
        $backorders = Order::with(['details' => function ($query) {
                        $query->where('backordered', '>', 0);
                    }])->get();
        return view('admin.index-backorders',compact('backorders'));
    }
    public function user_index(){
        $users = User::all();
        return view('admin.index-users',compact('users'));
    }
    public function option_index(){
        $option_groups = OptionGroup::all();
        return view('admin.index-options',compact('option_groups'));
    }
    public function special_index(){
        $special = Special::first();
        if(!$special){
            $special = Special::seed();
        }
        return view('admin.index-specials',compact('special'));
    }
}