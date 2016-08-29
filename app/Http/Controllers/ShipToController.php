<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ShipTo;
use App\User;
use Auth;

class ShipToController extends Controller
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
    public function create()
    {
        return view('shipto.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $shipto = new ShipTo;
        $shipto->user_id = Auth::user()->id;
        $shipto->name = $request->input('name');
        $shipto->address1 = $request->input('address1');
        $shipto->address2 = $request->input('address2');
        $shipto->city = $request->input('city');
        $shipto->state = $request->input('state');
        $shipto->zip = $request->input('zip');
        $shipto->save();
        return redirect()->route('user-profile')->with('success','Successfully added shipping address.');
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
        $shipto = ShipTo::find($id);
        return view('shipto.edit',compact('shipto'));
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
        $shipto = ShipTo::find($id);
        $shipto->user_id = Auth::user()->id;
        $shipto->name = $request->input('name');
        $shipto->address1 = $request->input('address1');
        $shipto->address2 = $request->input('address2');
        $shipto->city = $request->input('city');
        $shipto->state = $request->input('state');
        $shipto->zip = $request->input('zip');
        $shipto->save();
        return redirect()->route('user-profile')->with('success','Successfully updated shipping address.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $shipto = ShipTo::find($id);
        $shipto->delete();
        return redirect()->route('user-profile')->with('success', 'Deleted shipping address successfully.');
    }
}
