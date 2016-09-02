@extends('app')
@section('title'){{ $category->name }} :: @parent @stop
@section('content')
<div class="container main-container no-padding">
  <div class="col-md-8 col-x-12 main-col">
    <h1>Related Categories:</h1>
    <div class="row category-holder">
      @if($category->children)
        @foreach($category->children as $child) 
          <a class="category-item" href="{{ route('category-show',$child->slug) }}" title="{{ $child->name }}">
            @if($child->picture)
              <img src="{{ asset('pictures/'.$child->picture) }}" class="img-responsive" alt="{{ $child->name }}" />
            @else
              <img src="{{ asset('/images/noimg.gif') }}" class="img-responsive" alt="No Image Available" />
            @endif
            <p>{{ $child->name }}</p>
          </a>
        @endforeach
      @endif
    </div>
    @if($category->getBreadcrumbs())
      <ol class="breadcrumb">
        {!! $category->getBreadcrumbs() !!}
      </ol>
    @endif
    <div class="row product-holder">
    @if($category->childProducts())
      <?php //var_dump($category->childProducts()); ?>
      @foreach($category->childProducts() as $product)
        <a href="{{ route('product-show',$product->slug) }}" class="product-item" title="{{ $product->name }}">
          @if($product->picture)
            <img src="{{ asset('pictures/'.$product->picture) }}" alt="{{ $product->name }}" />
          @else
            <img src="{{ asset('/images/noimg.gif') }}" class="img-responsive" alt="No Image Available" />
          @endif
          <div class="product-info">
            <p>{{ $product->name }}</p>
            <p>Retail Price: {{ $product->min_msrp_string }}</p>
            @if(Auth::check())
              @if(Auth::user()->product_price_check($product->id))
                <p>Your Price: {{ Auth::user()->product_price_check($product->id)->price_string }}</p>
              @else
                <p>Your Price: {{ $product->min_price_string }}</p>
              @endif
            @else
              <p>Your Price: {{ $product->min_price_string }}</p>
            @endif
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
