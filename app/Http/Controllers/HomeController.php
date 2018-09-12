<?php namespace App\Http\Controllers;
use App\Category;
use App\OrderDetail;
use App\Product;
use App\Special;
use Illuminate\Http\Request;
use Mail;
use QuickBooksOnline\API\DataService\DataService;

class HomeController extends Controller {

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
	public function index()
	{
        $top_categories = Category::featured()->active()->orderBy('updated_at','desc')->take(4)->get();
        // $top_products = Product::featured()->active()->orderBy('updated_at','desc')->take(4)->get();
        $top_products = OrderDetail::with('product')->has('product')->groupBy('product_id')->orderByRaw('SUM(quantity) DESC')->take(3)->get();
		return view('home',compact('top_categories','top_products'));
	}
	public function contact(Request $request){
		if($request->has('email')){
			Mail::raw('Spam Blocked on WWMD', function ($m) use ($request) {
            	$m->to('lbodden@drivegroupllc.com', 'Leopold Bodden')->subject('Spam Blocked WWMD');
        	});
		}
		else{
			Mail::send('emails.contact', compact('request'), function ($m) use ($request) {
                $m->subject('WWMD Contact Form');
            	$m->to('brent@wwmdusa.com', 'Brent Weintraub');
            	$m->to('bw.wwmd@gmail.com', 'Brent Weintraub');
            	$m->to('wwmdusa@gmail.com', 'Brent Weintraub');
            	$m->bcc('lbodden@drivegroupllc.com', 'Leopold Bodden');
            	$m->replyTo($request->input('real_email'));
        	});
		}
		return redirect()->back()->with('mail-sent','Success');
	}

	public function aboutus(){
		return view('about-us');
	}
	public function contactus(){
		return view('contact-us');
	}

	public function registerOrLogin(){
		return view('auth.register-or-login');
	}

}
