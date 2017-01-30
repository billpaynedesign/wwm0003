<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Html\FormBuilder;
use App\User;
use App\Product;
use App\Picture;
use App\Commands;
use App\Category;
use App\ProductAttribute;
use App\Order;
use App\OrderDetail;
use App\OrderDetailBox;
use Auth;
use Carbon\Carbon;
use Session;
use Cart;
use Hashids;
use PDF;

class OrderController extends Controller {

	public function show($token){
		$order = Order::where('token','=',$token)->first();
		//dd(intval(Hashids::decode($token)));
		return view('order.index',compact('order'));
	}
	public function history(){
		$orders = Order::where('user_id', '=',Auth::user()->id)->orderBy('created_at','DESC')->get();
		return view('order.history',compact('orders'));
	}
	public function orderModal(Request $request){
		$order = Order::find(intval($request->input('id')));
		return view('order.order-modal',compact('order'));
	}
	public function edit($id){
		$order = Order::find(intval($id));
		//dd($order->products);
		//dd($order->invoice_array,$order->invoice_html_list);
		return view('order.edit',compact('order'));
	}
	public function update(Request $request){
		if($request->has('cancel')){
			return redirect()->route('admin-orders')->with('tab','orders');
		}else{
			$order = Order::find(intval($request->input('id')));
			
			$order->shippingname = $request->input('first_name').' '.$request->input('last_name');
			$order->first_name = $request->input('first_name');
			$order->last_name = $request->input('last_name');
			$order->address1 = $request->input('address1');
			$order->address2 = $request->input('address2');
			$order->city = $request->input('city');
			$order->state = $request->input('state');
			$order->zip = $request->input('zip');
			$order->save();

			foreach ($request->input('item_qty') as $qty_id => $qty) {
				$detail = OrderDetail::find(intval($qty_id));
				$detail->quantity = intval($qty);
				$detail->backordered = array_key_exists($qty_id, $request->input('backordered'))?$request->input('backordered')[$qty_id]:null;
				$detail->lot_number = array_key_exists($qty_id, $request->input('lot_number'))?$request->input('lot_number')[$qty_id]:null;
				$detail->expiration = array_key_exists($qty_id, $request->input('expiration'))?$request->input('expiration')[$qty_id]:null;
				$detail->save();
			}

			if($request->has('item_delete')){
				foreach ($request->input('item_delete') as $delete_id) {
					OrderDetail::destroy(intval($delete_id));
				}
			}

			return redirect()->route('admin-orders')->with(['success'=>'Order Updated successfully','tab'=>'orders']);
		}

	}
	public function status(Request $request){
		$order = Order::find(intval($request->input('id')));
		return view('admin.modals.order-status',compact('order'));
	}
	public function statusUpdate(Request $request){
		$order = Order::find($request->input('order_id'));
		foreach($request->input('shipped') as $detail_id => $value) {
			$detail = OrderDetail::find($detail_id);
			if($detail->product->has_lot_expiry && empty($request->input('lot_number')[$detail_id]) && empty($request->input('expiration')[$detail_id])){
				return redirect()->back()->with(['fail'=>'Lot number and expiration required before item can be marked as shipped.','order-status-failed'=>$order->id,'tab'=>'orders']);
			}
		}
		//dd($request->has('boxed'));
		if($request->has('shipped')){
			$box = new OrderDetailBox;
			$box->order_id = $request->input('order_id');
			$box->tracking = $request->input('tracking');
			$box->save();
		}
		foreach($request->input('shipped') as $detail_id => $value) {
			$detail = OrderDetail::find($detail_id);
			$detail->expiration = $request->input('expiration')[$detail_id];
			$detail->lot_number = $request->input('lot_number')[$detail_id];
			$detail->shipped = 1;
			$detail->shipped_date = Carbon::now();
			$detail->box_id = $box->id;
			$detail->save();
		}
		foreach($request->input('paid') as $detail_id => $value) {
			$detail = OrderDetail::find($detail_id);
			$detail->paid = 1;
			$detail->paid_date = Carbon::now();
			$detail->save();
		}
		if($request->has('invoice')){
			$details = OrderDetail::whereIn('id',array_keys($request->input('shipped')))->get();
			$pdf = PDF::loadView('order.invoice-details', compact('order','details'));
			$ds = DIRECTORY_SEPARATOR;
	        $path = public_path()."{$ds}invoices{$ds}{$order->id}";
	        if(!file_exists($path)){
	            mkdir($path,0777);
	        }
	        $filename = $path.'/invoice-'.Carbon::now()->tz('America/New_York')->format('m-d-Y-h-i').'.pdf';
			$pdf->save($filename);
			//return view('order.invoice-details',compact('order','details'));
			return redirect()->route('admin-orders')->with(['success'=>'Order Status updated.','tab'=>'orders']);
		}
		else{
			return redirect()->route('admin-orders')->with(['success'=>'Order Status updated.','tab'=>'orders']);
		}
	}
	public function delete($id){
		$order = Order::destroy(intval($id));
		$detail = OrderDetail::where('order_id','=',$id)->delete();
		return back()->with(['tab'=>'orders','success'=>'Order deleted successfully']);
	}
	public function editLine($id){
		$line = OrderDetail::find($id);
		return view('order.edit-orderdetail')->with(compact('line'));
	}
	public function editLineUpdate(Request $request, $id){
		$line = OrderDetail::find($id);
		$original = $line->quantity;
		if($original !== array_sum($request->input('line'))){
			return redirect()->back()->withInput()->with('fail','The quantities you entered did not add up to the original line quantity.');
		}
		else{
			$newlines = $request->input('line');
			rsort($newlines);
			$line->quantity = array_pop($newlines);
			$line->subtotal = intval($line->quantity)*floatval($line->product->price);
			$line->save();
			foreach ($newlines as $quantity) {
				$newline = new OrderDetail;
				$newline->product_id = $line->product_id;
				$newline->order_id = $line->order_id;
				$newline->quantity = $quantity;
				$newline->subtotal = intval($quantity)*floatval($line->product->price);
				$newline->save();
			}
			return redirect()->route('order-edit',$line->order_id)->with('success','Order split successfully');
		}
	}
	public function invoice($token){
		$order = Order::where('token',$token)->first();
		return view('order.invoice',compact('order'));
	}
	public function toggleBackordered($id){
		$order = Order::find($id);
		$order->backordered = $order->backordered?0:1;
		$order->save();
		return response()->json(['response' => $order->backordered, 'id' => $id]);
	}
	public function backordered(){
        $orders = Order::with(['details' => function ($query) {
                        $query->where('backordered', '>', 0);
                    }])->get();
		return view('order.backordered',compact('orders'));
	}
	public function productAdd(Request $request, $id){
		//dd($request->all());
		$product = Product::find($request->input('product_id'));
		$detail = new OrderDetail;
		$detail->product_id = $request->input('product_id');
		$detail->order_id = $id;
		$detail->quantity = 1;
		$detail->subtotal = $product->price;
		$detail->save();
		return redirect()->route('order-edit',$id)->with('success','Item added successfully!');
	}
}
