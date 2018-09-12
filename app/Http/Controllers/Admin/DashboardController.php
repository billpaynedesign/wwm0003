<?php namespace App\Http\Controllers\Admin;

use App\BillAccount;
use App\Category;
use App\Commands\CategoryHelper;
use App\Http\Controllers\AdminController;
use App\OptionGroup;
use App\PaymentTerm;
use App\Order;
use App\OrderDetails;
use App\Picture;
use App\Product;
use App\ProductAttribute;
use App\Special;
use App\User;
use App\Vendor;
use App\VendorBill;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

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
        if(request()->ajax()){
            return Datatables::eloquent(Category::with('parent')->with('products'))
                    ->editColumn('featured',function($category){
                        $html = '<button id="category-featured-'.$category->id.'"';
                        $html .= 'onclick="category_toggle_featured('.$category->id.');" class="btn btn-link">';

                        if($category->featured==1){
                            $html .= '<span class="text-yellow glyphicon glyphicon-star"></span>';
                        }
                        else{
                            $html .= '<span class="text-danger glyphicon glyphicon-remove"></span>';
                        }

                        $html .='</button>';

                        return $html;
                    })
                    ->editColumn('active',function($category){
                        $html = '<button id="category-active-'.$category->id.'"';
                        $html .= 'onclick="category_toggle_active('.$category->id.');" class="btn btn-link">';

                        if($category->active==1){
                            $html .= '<span class="text-success glyphicon glyphicon-ok"></span>';
                        }
                        else{
                            $html .= '<span class="text-danger glyphicon glyphicon-remove"></span>';
                        }

                        $html .='</button>';

                        return $html;
                    })
                    ->addColumn('items',function($category){
                        return $category->getProductsCount();
                    })
                    ->addColumn('action',function($category){
                        $picture = $category->picture?asset('pictures/'.$category->picture):asset('/images/noimg.gif');
                        return '
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit_category"  data-category-id="'.$category->id.'"  data-parent-id="'.$category->parent_id.'" data-name="'.$category->name.'" data-img="'.$picture.'"><span class="glyphicon glyphicon-edit"></span></button>
                        ';
                    })
                    ->make(true);
        }
        $categories = Category::all();
        $categoryHelper = new CategoryHelper($categories);

        return view('admin.index-categories', compact('categories','categoryHelper'));
    }
    public function product_index(){
        if(request()->ajax()){
            return Datatables::eloquent(Product::with('categories'))
                    ->editColumn('name',function($product){
                        return '<a href="'.route('product-show', $product->slug).'">'.$product->name.'</a>';
                    })
                    ->editColumn('active',function($product){
                        $html = '<button id="product-active-'.$product->id.'"';
                        $html .= 'onclick="product_toggle_active('.$product->id.');" class="btn btn-link">';

                        if($product->active==1){
                            $html .= '<span class="text-success glyphicon glyphicon-ok"></span>';
                        }
                        else{
                            $html .= '<span class="text-danger glyphicon glyphicon-remove"></span>';
                        }

                        $html .='</button>';

                        return $html;
                    })
                    ->addColumn('action',function($product){
                        return '
                        <button class="btn btn-info" data-toggle="modal" data-target="#order-info" title="'.$product->name.' Product Information" onclick="product_information('.$product->id.');">
                          <span class="fa fa-info"></span>
                        </button>
                        <a href="'.route('product-edit',$product->id).'" class="btn btn-warning" title="Edit '.$product->name.'">
                          <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </a>
                        <a href="'.route('product-delete',$product->id).'" class="btn btn-danger" title="Remove '.$product->name.'" onclick="return confirm('."'Are you sure you want to remove this product?'".');">
                          <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </a>
                        ';
                    })
                    ->make(true);
        }
        $categories = Category::all();
        $categoryHelper = new CategoryHelper($categories);
        // $products = Product::all();

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
    public function vendors(){
        $vendors = Vendor::all();
        return view('admin.index-vendors',compact('vendors'));
    }
    public function accounts_receivable(){
        $orders = Order::with('user')->whereHas('details', function($query){
                    $query->whereNull('paid')->orWhere('paid','!=','1');
                })->get();
        return view('admin.index-accounts-receivable',compact('orders'));
    }
    public function accounts_payable(Request $request){
        $bill_query = VendorBill::with('payment_term')->with('vendor');
        if(!$request->has('include_paid')) $bill_query->where('paid','=','0');
        $vendor_bills = $bill_query->get();
        $vendors = Vendor::all();
        $payment_terms = PaymentTerm::all();
        $accounts = BillAccount::all();
        return view('admin.index-accounts-payable',compact('vendor_bills','vendors','payment_terms','accounts'));
    }
}
