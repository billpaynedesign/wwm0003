<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductGroup;
use App\Option;
use App\OptionGroup;

class OptionGroupController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $option_group = new OptionGroup;
        $option_group->name = $request->input('option_group_name');
        $option_group->save();

        foreach ($request->input('option_names') as $option_name) {
            $option = new Option;
            $option->option_group_id = $option_group->id;
            $option->option = $option_name;
            $option->save();
        }

        return redirect()->route('admin-dashboard')->with(['success'=>'Product options added successfully','tab'=>'products']);
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
        $product_group = ProductGroup::find($id);
        $select_options = $product_group->option_group->options()->whereDoesntHave('products')->get();
        $can_add = true;
        if(count($product_group->products)===count($product_group->option_group->options)) $can_add = false;
        return view('option_group.edit', compact('product_group','select_options','can_add'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function group_product_select_group(Request $request){
        $options = OptionGroup::all();
        return view('option_group.select_group',compact('options'));
    }

    public function group_product_select_products(Request $request){
        $option = OptionGroup::find($request->input('group'));
        $products = Product::all();
        return view('option_group.select_products',compact('option', 'products'));
    }

    public function group_product_option_associate(Request $request){
        $option_group = OptionGroup::find($request->input('option_group_id'));
        $products = Product::whereIn('id',$request->input('products'))->get();
        return view('option_group.product_option_associate',compact('option_group','products'));
    }

    public function group_product_option_save(Request $request){
        $product_group = new ProductGroup;
        $product_group->option_group_id = $request->input('option_group_id');
        $product_group->save();

        $product_group->products()->attach(array_keys($request->input('product_associations')));

        foreach ($request->input('product_associations') as $product_id => $option_id) {
            $product = Product::find($product_id);
            $product->options()->attach($option_id);
        }
        
        return redirect()->route('admin-dashboard')->with(['success'=>'Products grouped successfully','tab'=>'products']);
    }

    public function group_product_option_add(Request $request, $id){
        $product_group = ProductGroup::find($id);
        $option = new Option;
        $option->option_group_id = $product_group->option_group_id;
        $option->option = $request->input('option_add');
        $option->save();

        return redirect()->route('option.edit', $id)->with(['success'=>'Option added successfully']);
    }
    public function group_product_product_add(Request $request, $id){
        $product_group = ProductGroup::find($id);
        if($product_group->products->contains($request->input('add_product_id'))){
            return redirect()->route('option.edit', $id)->with(['fail'=>'Product already in this group with an option']);
        }
        $product_group->products()->attach($request->input('add_product_id'));
        $option = Option::find($request->input('option_id'));
        if(!$option->products->contains($request->input('add_product_id'))){
            $option->products()->attach($request->input('add_product_id'));
        }

        return redirect()->route('option.edit', $id)->with(['success'=>'Product added successfully']);
    }

    public function group_product_delete(Request $request, $id){
        $product_group = ProductGroup::find($id);
        $product_group->products()->detach($request->input('product_id'));
        $option = Option::find($request->input('option_id'));
        $option->products()->detach($request->input('product_id'));
        if(count($product_group->products)===0){
            $product_group->delete();
            return redirect()->route('admin-dashboard')->with(['tab'=>'products']);
        }
        return redirect()->route('option.edit', $id)->with(['success'=>'Product deleted successfully']);
    }
}
