@extends('layout')

@section('keywords') @parent:: {{ $product->category?$product->category->name:'Uncategorized' }} :: {{ $product->name }} @stop
@section('title') @parent :: {{ $product->category?$product->category->name:'Uncategorized' }} :: {{ $product->name }} @stop

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $('#uom').change(function(){
    $('#msrp').html($(this).find('option:selected').data('msrp'));
    $('#price').html($(this).find('option:selected').data('price'));
  });
});
</script>
@endsection

@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container">
    <div id="col-main" class="col-xs-12">
      <div class="col-md-6 col-xs-12 text-center">
        <div class="form-group">
          @if($product->picture)
          <img src="{{ asset('pictures/'.$product->picture) }}" class="img-responsive" alt="{{ $product->name }}" />
          @else
          <img src="{{ asset('images/noimg.gif') }}" class="img-responsive" alt="No Image Available" />
          @endif 
        </div>
        @if(count($product->groups)>0)
          <div class="form-group">
            @foreach($product->groups as $product_group)
              <h4 class="text-left">{{ $product_group->option_group->name }}:</h4>
              <ul class="nav nav-pills product-nav-pills">
                @foreach($product_group->products as $option_product)
                  @if($option = $product_group->option_group->options()->whereHas('products', function($query) use($option_product){ $query->where('product_id', $option_product->id); })->first())
                    <li class="{{ $option_product->id===$product->id?'active':'' }}"><a href="{{ route('product-show', $option_product->slug) }}">{{ $option->option }}</a></li>
                  @endif
                @endforeach
              </ul>
            @endforeach
          </div>
        @endif
      </div>
      <div class="col-md-6 col-xs-12 product-details">

        <h1>{{ $product->name }}</h1>

        <div class="form-group">
          <p><strong>Item Number:</strong> {{ $product->item_number }}</p>
          <p><strong>Overview:</strong> {!! nl2br($product->short_description) !!}</p>
          @if(Auth::check() && !Auth::user()->no_pricing)
            <p><strong>Retail Price:</strong> <span id="msrp">{{ $product->min_msrp_string }}</span></p>
            @if(Auth::check())
              @if(Auth::user()->product_price_check($product->id))
                <p class="price">Your Price: <span id="price">{{ Auth::user()->product_price_check($product->id)->price_string }}</span></p>
              @else
                <p class="price">Your Price: <span id="price">{{ $product->min_price_string }}</span></p>
              @endif
            @else
              <p class="price">Your Price: <span id="price">{{ $product->min_price_string }}</span></p>
            @endif
          @endif
        </div>
        <form action="{{ route('add-to-cart') }}" method="post" role="form">
          @if($product->units_of_measure)
          <div class="form-group form-inline"> 
            <label for="uom">Unit of Measure</label><br/>
            <select id="uom" name="uom" class="form-control">
              @if(Auth::check() && !Auth::user()->no_pricing)
                @if(Auth::user()->product_price_check($product->id))
                  @foreach($product->units_of_measure()->orderBy('price','desc')->get() as $uom)
                    <option value="{{ $uom->id }}" data-price="{{ Auth::user()->uom_price_check($uom->id)->price_string }}" data-msrp="{{ $uom->msrp_string }}">{{ $uom->name }} - {{ Auth::user()->uom_price_check($uom->id)->price_string }}</option>
                  @endforeach
                @else
                  @foreach($product->units_of_measure()->orderBy('price','desc')->get() as $uom)
                    <option value="{{ $uom->id }}" data-price="{{ $uom->price_string }}" data-msrp="{{ $uom->msrp_string }}">{{ $uom->name }} - {{ $uom->price_string }}</option>
                  @endforeach
                @endif
              @else
                @foreach($product->units_of_measure()->orderBy('price','desc')->get() as $uom)
                  <option value="{{ $uom->id }}" data-price="{{ $uom->price_string }}" data-msrp="{{ $uom->msrp_string }}">{{ $uom->name }}</option>
                @endforeach
              @endif
            </select>
          </div>
          @endif
          <div class="form-group form-inline"> 
            <label for="quantity">QTY</label><br/>
            <input type="number" name="quantity" id="quantity" value="1" maxlength="5" class="form-control">
          </div>
          <input type="hidden" name="id" value="{{ $product->id }}"/>
          <button type="submit" name="_token" class="btn btn-addcart" value="{{ csrf_token() }}" title="Add to cart">Add to cart</button>
          
        </form>
        <hr>
      </div>
      <div class="col-xs-12">
        <p><strong class="text-blue">Details:</strong></p>
        <p>{!! nl2br($product->description) !!}</p>
      </div>
    </div>
  </div>
</div>

@endsection
