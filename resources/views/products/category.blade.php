@extends('layout')
@section('title'){{ $category->name }} :: @parent @stop
@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container">
    <div id="col-main" class="col-xs-12">
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
        <?php
          //initialize empty array to store groups already shown. 
          //initialize show to true so if a product doesn't have a group it will still be shown
          $product_groups = array(); $show = true;
        ?>
        @foreach($category->childProducts() as $product)
          <?php
            //reset to show until we decide differently so product will be shown
            $show = true;
            //check if this product belongs to any groups
            if(count($product->groups)){
              //reset to false unless this the product group has not been shown
              $show = false;
              //get the ids of the groups this product belongs to.
              //flip that array of id values so the ids are now the keys in the array
              //check the difference between the current groups we've shown and the new groups from this product
              $check = array_diff_key(array_flip($product->groups->lists('id')->toArray()), $product_groups);
              //array_diff_key returns the keys from the first array that wasn't in the array checked
              //so count the array length if greater than 0
              if(count($check)>0){
                //one or more of these groups haven't been represented by another product on the page so show it
                $show = true;
                //add the returned keys to the array we are keeping track of
                $product_groups = $product_groups + $check;
              }
            }
          ?>
          @if($show)
            <a href="{{ route('product-show',$product->slug) }}" class="product-item" title="{{ $product->name }}">
              @if($product->picture)
                <img src="{{ asset('pictures/'.$product->picture) }}" alt="{{ $product->name }}" />
              @else
                <img src="{{ asset('/images/noimg.gif') }}" class="img-responsive" alt="No Image Available" />
              @endif
              <div class="product-info">
                <p>{{ $product->name }}</p>
                @if(Auth::check() && !Auth::user()->no_pricing)
                  <p>Retail Price: {{ $product->min_msrp_string }}</p>
                  @if(Auth::user()->product_price_check($product->id))
                    <p>Your Price: {{ Auth::user()->product_price_check($product->id)->price_string }}</p>
                  @else
                    <p>Your Price: {{ $product->min_price_string }}</p>
                  @endif
                @endif
              </div>
              <div class="btn btn-product-moreinfo">More Info <span class="glyphicon glyphicon-chevron-right"></span></div>
            </a>
          @endif
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection
