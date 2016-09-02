<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\UnitOfMeasure;

class UnitOfMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $uom = new UnitOfMeasure;
        $uom->product_id = $request->input('product_id');
        $uom->name = $request->input('name');
        $uom->price = $request->input('price');
        $uom->msrp = $request->input('msrp');
        $uom->save();
        if($request->ajax()) return response()->json(compact('uom'));

        return redirect()->back()->with('success','Successfully created Unit of Measure');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $uom = UnitOfMeasure::find($id);
        $uom->product_id = $request->input('product_id');
        $uom->name = $request->input('name');
        $uom->price = $request->input('price');
        $uom->msrp = $request->input('msrp');
        $uom->save();
        if($request->ajax()) return response()->json(compact('uom'));

        return redirect()->back()->with('success','Successfully updated Unit of Measure');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $uom = UnitOfMeasure::find($id);
        $uom->delete();
        $success = true;
        if($request->ajax()) return response()->json(compact('uom','success'));

        return redirect()->back()->with('success','Successfully updated Unit of Measure');
    }
}
