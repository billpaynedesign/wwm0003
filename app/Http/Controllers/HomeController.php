<?php namespace App\Http\Controllers;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Mail;
class HomeController extends Controller {

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
	public function index()
	{
        $featured = Category::featured()->active()->get();
		return view('home',compact('featured'));
	}
	public function contact(Request $request){
		if($request->has('email')){
			Mail::raw('Spam Blocked on WWMD', function ($m) use ($request) {
            	$m->to('lbodden@drivegroupllc.com', 'Leopold Bodden')->subject('Spam Blocked WWMD');
        	});
		}
		else{
			Mail::send('emails.contact', compact('request'), function ($m) use ($request) {
            	$m->to('bw@wwmdusa.com', 'Brent Weintraub')->subject('WWMD Contact Form');
            	$m->to('bw.wwmd@gmail.com', 'Brent Weintraub')->subject('WWMD Contact Form');
            	$m->to('wwmdusa@gmail.com', 'Brent Weintraub')->subject('WWMD Contact Form');
            	$m->bcc('lbodden@drivegroupllc.com', 'Leopold Bodden')->subject('WWMD Contact Form');
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
