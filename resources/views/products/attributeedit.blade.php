@extends('app')


@section('content')
<div class="container container-main">
    <h1>Edit {{ $attribute->name }}</h1>
    <div class="col-md-10 col-md-offset-1">
    <form action="{{ route('attribute-update') }}" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="option">Option: </label>
        <input type="text" name="option" id="option" class="form-control" value="{{ $attribute->option }}" />
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ $attribute->price }}" step="0.01"/>
        <p class="help-block">Leave at 0.00 if you would like to use the product amount.</p>
        <label for="msrp">MSRP:</label>
        <input type="number" name="msrp" id="msrp" class="form-control" value="{{ $attribute->msrp }}" step="0.01"/>
        <p class="help-block">Leave at 0.00 if you would like to use the product amount.</p>
        <label for="discount">Discount:</label>
        <input type="number" name="discount" id="discount" class="form-control" value="{{ $attribute->discount }}" step="0.01"/>
        <label for="inStock">InStock:</label>
        <input type="number" name="inStock" id="inStock" class="form-control" value="{{ $attribute->inStock }}" />
        <!--<div class="checkbox">
          <label>
            <input type="checkbox" name="featured" id="featured" {{ $attribute->featured?'checked':'' }}> Featured
          </label>
        </div>-->
        <div class="checkbox">
          <label>
            <input type="checkbox" name="available" id="available" {{ $attribute->available?'checked':'' }}> Available
          </label>
        </div>
        
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id" value="{{ $attribute->id }}"/>
          <button type="submit" name="cancel" value="true" class="btn btn-default">Cancel</button>
          <button type="submit" name="submit" value="true" class="btn btn-normal">Submit</button>
        </div>
    </form>
  </div>
</div>
@endsection
