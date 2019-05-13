<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Quote;
use App\QuoteProduct;
use Yajra\Datatables\Facades\Datatables;

class QuoteController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quotes.create');
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
            'email' => 'email'
        ]);

        $quote = Quote::create([
            'billing_address1' => $request->input('billing_address1'),
            'billing_address2' => $request->input('billing_address2'),
            'billing_city' => $request->input('billing_city'),
            'billing_state' => $request->input('billing_state'),
            'billing_zip' => $request->input('billing_zip'),
            'shipping_address1' => $request->input('shipping_address1'),
            'shipping_address2' => $request->input('shipping_address2'),
            'shipping_city' => $request->input('shipping_city'),
            'shipping_state' => $request->input('shipping_state'),
            'shipping_zip' => $request->input('shipping_zip'),
            'email' => $request->input('email'),
            'rfq_num' => $request->input('rfq_num')
        ]);

        $products = $request->input('products');
        $uoms = $request->input('uoms');
        $quantities = $request->input('quantities');
        $price = $request->input('price');

        $total = 0;

        foreach ($products as $key => $product) {
            $item_total = (int)$quantities[$key]*(float)$price[$key];
            $quote_product = QuoteProduct::create([
                'quantity' => $quantities[$key],
                'product_id' => $products[$key],
                'uom_id' => $uoms[$key],
                'price' => $price[$key],
                'item_total' => $item_total
            ]);
            $total += $item_total;
            $quote->products()->save($quote_product);
        }

        $quote->total = $total;
        $quote->save();

        return redirect()->route('quote-export',$quote->id);
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
        $quote = Quote::findOrFail($id);
        return view('quotes.export',compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quote = Quote::findOrFail($id);
        return view('quotes.edit',compact('quote'));
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
        $quote = Quote::findOrFail($id);

        $this->validate($request,[
            'email' => 'email'
        ]);

        $total = 0;

        if($request->has('old_quantities') && count($request->input('old_quantities'))){
            $old_quantities = $request->input('old_quantities');
            $old_price = $request->input('old_price');

            foreach ($old_quantities as $key => $quantity) {
                $detail = QuoteProduct::find($key);
                if($detail){
                    $item_total = (int)$old_quantities[$key]*(float)$old_price[$key];

                    $quote_product = $detail->update([
                        'quantity' => $old_quantities[$key],
                        'price' => $old_price[$key],
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
            $price = $request->input('price');

            foreach ($products as $key => $product) {
                $item_total = (int)$quantities[$key]*(float)$price[$key];
                $quote_product = QuoteProduct::create([
                    'quantity' => $quantities[$key],
                    'product_id' => $products[$key],
                    'uom_id' => $uoms[$key],
                    'price' => $price[$key],
                    'item_total' => $item_total
                ]);
                $total += (float)$item_total;
                $quote->products()->save($quote_product);
            }
        }

        $quote->update([
            'billing_address1' => $request->input('billing_address1'),
            'billing_address2' => $request->input('billing_address2'),
            'billing_city' => $request->input('billing_city'),
            'billing_state' => $request->input('billing_state'),
            'billing_zip' => $request->input('billing_zip'),
            'shipping_address1' => $request->input('shipping_address1'),
            'shipping_address2' => $request->input('shipping_address2'),
            'shipping_city' => $request->input('shipping_city'),
            'shipping_state' => $request->input('shipping_state'),
            'shipping_zip' => $request->input('shipping_zip'),
            'email' => $request->input('email'),
            'rfq_num' => $request->input('rfq_num'),
            'total' => $total
        ]);

        if($request->has('deletedetails') && count($request->input('deletedetails'))){
            $quote->products()->whereIn('id',$request->input('deletedetails'))->delete();
        }

        return redirect()->route('quote-edit',$quote->id);
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

    public function statusUpdate(Request $request, $id)
    {
        $quote = Quote::withTrashed()->findOrFail($id);

        $this->validate($request,[
            'status' => 'required|in:Accepted,Open,Declined,Archived'
        ]);

        $quote->status = $request->input('status');
        $quote->save();

        if($request->input('status') == 'Archived'){
            $quote->delete();
        }
        elseif($quote->trashed()){
            $quote->restore();
        }

        return response()->json(['success' => true]);
    }
}
