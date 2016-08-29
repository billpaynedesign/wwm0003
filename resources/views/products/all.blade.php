@extends('app')
@section('title')Products :: @parent @stop
@section('content')
<div class="container main-container no-padding">
  <div class="col-md-8 col-x-12 main-col">
    <h1>Products</h1>
    <div class="row product-holder">
    @if($products)
      @foreach($products as $product)
        <a href="{{ route('product-show',$product['slug']) }}" class="product-item" title="{{ $product['name'] }}">
          <img src="{{ asset('pictures/'.$product['picture']) }}" alt="{{ $product['name'] }}" />
          <div class="product-info">
            <p>{{ $product['name'] }}</p>
            <p>Retail Price: ${{ \number_format($product['msrp'],2) }}</p>
            <p>Your Price: ${{ \number_format($product['price'],2) }}</p>
          </div>
          <div class="btn btn-product-moreinfo">More Info <span class="glyphicon glyphicon-chevron-right"></span></div>
        </a>
      @endforeach
    @endif
    </div>
  </div>
  @include('partial.sidebar-contact-col4')
</div>

@endsection
