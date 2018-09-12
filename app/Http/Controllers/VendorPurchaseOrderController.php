<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\VendorPoDetail;
use App\Vendor;
use App\VendorPurchaseOrder;
use App\UnitOfMeasure;

class VendorPurchaseOrderController extends Controller
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
        $total = 0;

        foreach ($products as $key => $product) {
            $uom = UnitOfMeasure::find($uoms[$key]);
            $item_total = (int)$quantities[$key]*(float)$uom->price;
            $po_detail = VendorPoDetail::create([
                'quantity' => $quantities[$key],
                'product_id' => $products[$key],
                'uom_id' => $uoms[$key],
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
        //
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
        //
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
