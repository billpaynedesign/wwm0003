<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\VendorPurchaseOrder;
use Yajra\Datatables\Facades\Datatables;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'phone' => 'string',
            'attn' => 'string',
            'address' => 'string',
            'address2' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'string'
        ]);

        $vendor = Vendor::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'attn' => $request->input('attn'),
                'address' => $request->input('address'),
                'address2' => $request->input('address2'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip' => $request->input('zip')
            ]);
        return redirect()->route('admin-vendors')->with('success','Vendor created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax()){
            return Datatables::eloquent(VendorPurchaseOrder::where('vendor_id',$id)->with('vendor')->select('vendor_purchase_orders.*'))
                    ->editColumn('id',function($purchase_order){
                        return $purchase_order->invoice_num;
                    })
                    ->editColumn('total',function($purchase_order){
                        return '$'.number_format($purchase_order->total,2);
                    })
                    ->editColumn('date',function($purchase_order){
                        return $purchase_order->date->format('m-d-Y');
                    })
                    ->addColumn('action',function($purchase_order){
                        $html = '<a href="'.route('vendor-purchase-order-export',$purchase_order->id).'" class="btn btn-primary">
                            <span class="fa fa-file-pdf"></span>
                        </a> ';
                        if($purchase_order->vendor && $purchase_order->vendor->email){
                            $html .= "<a href='mailto:{$purchase_order->vendor->email}&subject=".rawurlencode("Purchase Order #{$purchase_order->invoice_num} from World Wide Medical Distributors")."&body=".rawurlencode("Dear {$purchase_order->vendor->name}\n\nPurchase Order #{$purchase_order->invoice_num} is attached. Please review and fill at your earliest convenience.\n\nThank You,\nWorld Wide Medical Distributors")."' class='btn btn-success'><span class='fa fa-envelope'></span></a> ";
                        }
                        $html .= '
                        <a href="#order-info" data-toggle="modal" data-target="#order-info"  data-poid="'.$purchase_order->id.'" class="btn btn-info">
                            <span class="fa fa-info"></span>
                        </a>
                        <a href="'.route('vendor-purchase-order-edit',$purchase_order->id).'" class="btn btn-warning">
                            <span class="fa fa-edit"></span>
                        </a>
                        ';
                        return $html;
                    })
                    ->make(true);
        }
        $vendor = Vendor::findOrFail($id);
        $ajax_url = route('vendor-show',$id);
        return view('admin.index-vendor-purchase-orders',compact('ajax_url'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.edit',compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'phone' => 'string',
            'attn' => 'string',
            'address' => 'string',
            'address2' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'string'
        ]);
        $vendor = Vendor::findOrFail($id);
        $vendor->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'attn' => $request->input('attn'),
                'address' => $request->input('address'),
                'address2' => $request->input('address2'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip' => $request->input('zip')
            ]);
        return redirect()->route('admin-vendors')->with('success','Vendor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
