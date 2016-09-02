@extends('app')

@section('scripts')
<script type="text/javascript">
function remove_uom(id,name){
  if(confirm('Are you sure you want to remove the Unit of Measure: '+name)){
    $.post("{{ route('unit_of_measure.destroy','') }}/"+id,{_method: 'DELETE',_token:'{{ csrf_token() }}',id:id},function(data){
      $('#uom-single-'+data['uom']['id']).remove();
    });
  }
  return false;
}
function add_uom(){
  var html = '<div class="uom_single"> <hr/> <div class="form-group"> <button class="btn btn-danger" onclick="$(this).parent().parent().remove();"><span class="fa fa-trash"></span></button> </div> <div class="form-group"> <label for="uom">Unit of Measure</label> <input type="text" id="uom" name="uom_new[]" class="form-control" required /> </div> <div class="form-group"> <label for="msrp">MSRP:</label> <input type="number" id="msrp" name="msrp_new[]" step="0.01" min="0" class="form-control" required /> </div> <div class="form-group"> <label for="price">Price:</label> <input type="number" id="price" name="price_new[]" step="0.01" min="0" class="form-control" required /> </div> </div>'
  $("#uom_groups").append(html);
  return false;
}
</script>
@endsection
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
          <label for="category">Category</label>
          <select name="category" id="category" class="form-control" required>
            <option value="">-- Select a Category --</option>
            {!! $categoryHelper->htmlSelectOptions($product->category?$product->category->id:null) !!}
          </select>
        </div>
        <div class="form-group">
          <label for="item_number">Item Number</label>
          <input type="text" class="form-control" id="item_number" name="item_number" placeholder="Item Number" value="{{ $product->item_number }}" />
        </div>
        <div id="old_uom_groups">
          @if($product->units_of_measure)
            @foreach($product->units_of_measure as $uom)
              <div id="uom-single-{{ $uom->id }}"class="old_uom_single">
                <hr/>
                <div class="form-group">
                  <a class="btn btn-danger" onclick="remove_uom({{ $uom->id }},'{{ $uom->name }}');"><span class="fa fa-trash"></span>&nbsp;Remove {{ $uom->name }}</a>
                </div>
                <div class="form-group">
                  <label for="uom-{{ $uom->id }}">Unit of Measure</label>
                  <input type="text" id="uom-{{ $uom->id }}" name="uom[{{ $uom->id }}]" class="form-control" value="{{ $uom->name }}" required />
                </div>
                <div class="form-group">
                  <label for="msrp-{{ $uom->id }}">MSRP:</label>
                  <input type="number" id="msrp-{{ $uom->id }}" name="msrp[{{ $uom->id }}]" step="0.01" min="0" class="form-control" value="{{ $uom->msrp }}" required />
                </div>
                <div class="form-group">
                  <label for="price-{{ $uom->id }}">Price:</label>
                  <input type="number" id="price-{{ $uom->id }}" name="price[{{ $uom->id }}]" step="0.01" min="0" class="form-control" value="{{ $uom->price }}" required />
                </div>
              </div>
            @endforeach
          @endif
        </div>
        <div id="uom_groups"></div>
        <div class="form-group">
          <hr/>
          <a class="btn btn-success" onclick="add_uom();"><span class="fa fa-plus"></span>&nbsp;Add Unit of Measure</a>
          <hr/>
        </div>
        <div class="form-group">
          <label for="manufacturer">Manufacturer</label>
          <input type="text" name="manufacturer" id="manufacturer" class="form-control" value="{{ $product->manufacturer }}" />
         </div>
        <div class="form-group">
          <label for="shortdescription">Overview</label>
          <textarea class="form-control" id="shortdescription" name="shortdescription" placeholder="Overview">{{ $product->short_description }}</textarea>
        </div>
        <div class="form-group">
          <label for="productdescription">Details</label>
          <textarea class="form-control" id="productdescription" name="productdescription" placeholder="Details">{{ $product->description }}</textarea>
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
