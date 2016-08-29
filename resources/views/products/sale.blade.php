@extends('app')
@section('keywords') @parent , Products On Sale! @stop
@section('title') @parent :: On Sale! @stop
@section('content')
<div class="container container-main">
  <div class="row">
    <h1>On Sale!</h1>
  </div>
  <div class="row">
    <div class="col-xs-12 category-holder">
      @foreach($products as $product)
      <div class="col-md-4 col-sm-6 col-xs-12">
        <a href="{{ route('product-show',$product->slug) }}" class="category-item">
          <div class="category-img">
            <img src="{{ asset('pictures/'.$product->picture) }}" class="img-responsive" />
          </div>
          <h1>{{ $product->name }}</h1>
          <p>{!! $product['discountAvailable']?'<span class="strike-through">$'.\number_format($product['msrp'],2).'</span> <strong class="text-orange">$'.\number_format($product['msrp'] - $product['discount'],2).'</strong>':'$'.\number_format($product['msrp'],2) !!} <button class="btn btn-default btn-buynow">Buy Now</button> </p>
          
        </a>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
