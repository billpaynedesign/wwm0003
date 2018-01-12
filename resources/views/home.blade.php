@extends('layout')
@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
      <div id="col-main" class="col-xs-12">
        <h2>Browse Top Categories: <a href="{{ route('category-index') }}" id="view-all-categories">View all categories</a></h2>
        <div id="top-category-holder">
          @if($top_categories)
            @foreach($top_categories as $tc)
              <div class="top-category">
                <div class="top-category-header">
                  <?php
                  switch($tc->slug):
                    case 'diagnostic-equipment':
                    case 'equipment-supplies':
                    case 'medical-supplies':
                    case 'syringe-needles':
                      echo '<img src="'.asset('images/category-icon-'.$tc->slug.'.png').'">';
                      break;
                    default:
                      echo '<img src="'.asset('images/category-icon-default.png').'">';
                  endswitch;
                  ?>
                  {{ ucwords(strtolower($tc->name)) }}
                </div>

                <div class="top-category-content">
                  @if(!empty($tc->description))
                    {{ str_limit($tc->description, 150) }}
                  @else
                    Find out more about this category.<br>
                  @endif
                  <br>
                  <a href="{{ route('category-show',$tc->slug) }}" class="pull-right">Learn More ></a>
                  <div class="clearfix"></div>
                </div>
              </div>
            @endforeach
          @endif
        </div><!-- #top-category-holder -->

        <h2>Top Products: </h2>
        <div id="top-product-holder">
          @if($top_products)
            @foreach($top_products as $tp)
              <a href="{{ route('product-show',$tp->slug) }}" class="top-product col-sm-4 no-padding" title="{{ $tp->name }}">
                <div class="col-md-5 top-product-inner">
                  @if($tp->picture)
                    <img src="{{ asset('pictures/'.$tp->picture) }}" alt="{{ $tp->name }}" class="img-responsive center-block" />
                  @else
                    <img src="{{ asset('/images/noimg.gif') }}" class="img-responsive center-block" alt="No Image Available" />
                  @endif
                </div>
                <div class="col-md-7 top-product-inner">
                  <p>Item Number: {{ $tp->item_number }}</p>
                  <h3>{{ $tp->name }}</h3>
                </div>
              </a>
            @endforeach
          @endif
        </div><!-- #top-product-holder -->
    </div>
  </div>
</div>
@endsection