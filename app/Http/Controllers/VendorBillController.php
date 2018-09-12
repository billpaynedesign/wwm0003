<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillAccount;
use App\VendorBill;

class VendorBillController extends Controller
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
        $this->validate($request,[
            'name' => 'required|string',
            'vendor' => 'required|integer',
            'date' => 'required|date',
            'reference_num' => 'required|string',
            'amount' => 'required|numeric',
            'payment_terms' => 'required|integer'
        ]);
        if($request->input('account')==='other'){
            $this->validate($request,[
                'account_name' => 'required|string'
            ]);
            $account = BillAccount::create(['name'=>$request->input('account_name')]);
        }
        else{
            $account = BillAccount::find($request->input('account'));
        }
        if($account){
            $vendor_bill = VendorBill::create([
                'name' => $request->input('name'),
                'vendor_id' => $request->input('vendor'),
                'date' => $request->input('date'),
                'reference_num' => $request->input('reference_num'),
                'amount' => $request->input('amount'),
                'payment_term_id' => $request->input('payment_terms'),
                'paid' => $request->has('paid'),
                'bill_account_id' => $account->id
            ]);
            return redirect()->route('admin-accounts-payable')->with('success','Bill created successfully');
        }
        else{
            return redirect()->back()->withInput();
        }
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

    public function update_paid($id){
        $bill = VendorBill::findOrFail($id);
        $bill->paid = 1;
        $bill->save();
        return redirect()->route('admin-accounts-payable');
    }
}
