<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\VendorPoDetail;
use App\Vendor;
use App\VendorPurchaseOrder;
use App\UnitOfMeasure;
use Yajra\Datatables\Facades\Datatables;

class VendorPurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            return Datatables::eloquent(VendorPurchaseOrder::with('vendor')->select('vendor_purchase_orders.*'))
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
                        $html = '<a href="'.route('vendor-purchase-order-export',$purchase_order->id).'" class="btn btn-primary" title="Print Purchase Order #'.$purchase_order->invoice_num.'">
                            <span class="fa fa-file-pdf"></span>
                        </a> ';
                        if($purchase_order->vendor && $purchase_order->vendor->email){
                            $html .= "<a href='mailto:{$purchase_order->vendor->email}?subject=".rawurlencode("Purchase Order #{$purchase_order->invoice_num} from World Wide Medical Distributors")."&body=".rawurlencode("Dear {$purchase_order->vendor->name}\n\nPurchase Order #{$purchase_order->invoice_num} is attached. Please review and fill at your earliest convenience.\n\nThank You,\nWorld Wide Medical Distributors")."' class='btn btn-success' title='Send email to {$purchase_order->vendor->name} {$purchase_order->vendor->email}'><span class='fa fa-envelope'></span></a> ";
                        }
                        $html .= '
                        <a href="#order-info" data-toggle="modal" data-target="#order-info"  data-poid="'.$purchase_order->id.'" class="btn btn-info" title="View Purchase Order #'.$purchase_order->invoice_num.'">
                            <span class="fa fa-info"></span>
                        </a>
                        <a href="'.route('vendor-purchase-order-edit',$purchase_order->id).'" class="btn btn-warning" title="Edit Purchase Order #'.$purchase_order->invoice_num.'">
                            <span class="fa fa-edit"></span>
                        </a>
                        ';
                        return $html;
                    })
                    ->make(true);
        }
        $ajax_url = route('vendor-purchase-order-index');
        return view('admin.index-vendor-purchase-orders',compact('ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::all();
        return view('purchase-order.create',compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'vendor' => 'required',
            'date' => 'required|date'
        ]);

        $purchase_order = VendorPurchaseOrder::create([
            'vendor_id' => $request->input('vendor'),
            'date' => $request->input('date')
        ]);

        $products = $request->input('products');
        $uoms = $request->input('uoms');
        $quantities = $request->input('quantities');
        $notes = $request->input('notes');
        $reordernums = $request->input('reordernums');
        $cost = $request->input('cost');

        $total = 0;

        foreach ($products as $key => $product) {
            $uom = UnitOfMeasure::find($uoms[$key]);
            $item_total = (int)$quantities[$key]*(float)$cost[$key];
            $po_detail = VendorPoDetail::create([
                'quantity' => $quantities[$key],
                'product_id' => $products[$key],
                'uom_id' => $uoms[$key],
                'note' => $notes[$key],
                'reorder_number' => $reordernums[$key],
                'cost' => $cost[$key],
                'item_total' => $item_total
            ]);
            $total += $item_total;
            $purchase_order->details()->save($po_detail);
        }

        $purchase_order->total = $total;
        $purchase_order->save();

        return redirect()->route('vendor-purchase-order-export',$purchase_order->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Export the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export($id)
    {
        $purchase_order = VendorPurchaseOrder::findOrFail($id);
        return view('purchase-order.export',compact('purchase_order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase_order = VendorPurchaseOrder::findOrFail($id);
        return view('purchase-order.edit',compact('purchase_order'));
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
        $purchase_order = VendorPurchaseOrder::findOrFail($id);

        $this->validate($request,[
            'date' => 'required|date'
        ]);

        $total = 0;

        if($request->has('old_quantities') && count($request->input('old_quantities'))){
            $old_quantities = $request->input('old_quantities');
            $old_notes = $request->input('old_notes');
            $old_reordernums = $request->input('old_reordernums');
            $old_cost = $request->input('old_cost');

            foreach ($old_quantities as $key => $quantity) {
                $detail = VendorPoDetail::find($key);
                if($detail){
                    $item_total = (int)$old_quantities[$key]*(float)$old_cost[$key];

                    $po_detail = $detail->update([
                        'quantity' => $old_quantities[$key],
                        'note' => $old_notes[$key],
                        'reorder_number' => $old_reordernums[$key],
                        'cost' => $old_cost[$key],
                        'item_total' => $item_total
                    ]);
                    $total += (float)$item_total;
                }
            }
        }


        if($request->has('products') && count($request->input('products'))){
            $products = $request->input('products');
            $uoms = $request->input('uoms');
            $quantities = $request->input('quantities');
            $notes = $request->input('notes');
            $reordernums = $request->input('reordernums');
            $cost = $request->input('cost');

            foreach ($products as $key => $product) {
                $uom = UnitOfMeasure::find($uoms[$key]);
                $item_total = (int)$quantities[$key]*(float)$cost[$key];
                $po_detail = VendorPoDetail::create([
                    'quantity' => $quantities[$key],
                    'product_id' => $products[$key],
                    'uom_id' => $uoms[$key],
                    'note' => $notes[$key],
                    'reorder_number' => $reordernums[$key],
                    'cost' => $cost[$key],
                    'item_total' => $item_total
                ]);
                $total += (float)$item_total;
                $purchase_order->details()->save($po_detail);
            }
        }

        $purchase_order->update(['date' => $request->input('date'), 'total' => $total]);

        if($request->has('deletedetails') && count($request->input('deletedetails'))){
            $purchase_order->details()->whereIn('id',$request->input('deletedetails'))->delete();
        }

        return redirect()->route('vendor-purchase-order-edit',$purchase_order->id);
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
