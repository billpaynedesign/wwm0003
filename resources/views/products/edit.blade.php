@extends('app')


@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Edit {{ $product->name }}</h1>
    <div class="col-xs-3">
      <img src="{{ asset($product->picture?'pictures/'.$product->picture:'images/noimg.gif') }}" class="img-responsive center-block" />
    </div>
    <div class="col-md-8 col-md-offset-1 col-xs-9">
      <form action="{{ route('product-update') }}" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Name: </label>
          <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" />
        </div>
        <div class="form-group">
          <label for="manufacturer">Manufacturer</label>
          <input type="text" name="manufacturer" id="manufacturer" class="form-control" value="{{ $product->manufacturer }}" />
         </div>
        <div class="form-group">
          <label for="price">Price</label>
          <input type="number" name="price" id="price" class="form-control" step="0.01" min="0"  value="{{ $product->price }}" />
         </div>
        <div class="form-group">
          <label for="msrp">MSRP</label>
          <input type="number" name="msrp" id="msrp" class="form-control" step="0.01" min="0"  value="{{ $product->msrp }}" />
         </div>
        <div class="form-group">
          <label for="item_number">Item Number</label>
          <input type="text" class="form-control" id="item_number" name="item_number" placeholder="Item Number" value="{{ $product->item_number }}" />
        </div>
        <div class="form-group">
          <label for="category">Category</label>
          <select name="category" id="category" class="form-control" required>
            <option value="">-- Select a Category --</option>
            {!! $categoryHelper->htmlSelectOptions($product->category?$product->category->id:null) !!}
          </select>
        </div>
        <div class="form-group">
          <label for="productdescription">Product Description</label>
          <textarea class="form-control" id="productdescription" name="productdescription" placeholder="Product Description">{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
          <label for="shortdescription">Short Description</label>
          <textarea class="form-control" id="shortdescription" name="shortdescription" placeholder="Short Description">{{ $product->short_description }}</textarea>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="active" id="active" {{ $product->active?'checked':'' }}> Available
            </label>
          </div>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="has_lot_expiry" id="has_lot_expiry" value="1" {{ $product->has_lot_expiry?'checked':'' }}> Require Lot Number &amp; Expiry Date?
            </label>
          </div>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" id="require_license" name="require_license" value="1" {{ $product->require_license?'checked':'' }}/> Require customer to have license?
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="note">Admin Note:</label>
          <textarea id="note" name="note" class="form-control">{!! nl2br($product->note) !!}</textarea>
        </div>
        <div class="form-group">
          <label for="image">Item Picture</label>
          <input type="file" id="image" name="image">
          <p class="help-block">If no picture is chosen the existing product picture will be used.</p>


          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id" value="{{ $product->id }}"/>
          <button type="submit" name="cancel" value="true" class="btn btn">Cancel</button>
          <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<hr/>
@endsection
