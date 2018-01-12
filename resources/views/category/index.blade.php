@extends('layout')
@section('title')All Categories :: @parent @stop
@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container">
    <div id="col-main" class="col-xs-12">
      <h1>All Categories:</h1>
      <div class="col-xs-12 no-padding category-holder">
        @if($categories)
          @foreach($categories as $category) 
            <a class="category-item" href="{{ route('category-show',$category->slug) }}" title="{{ $category->name }}">
              @if($category->picture)
                <img src="{{ asset('pictures/'.$category->picture) }}" class="img-responsive" alt="{{ $category->name }}" />
              @else
                <img src="{{ asset('/images/noimg.gif') }}" class="img-responsive" alt="No Image Available" />
              @endif
              <p>{{ $category->name }}</p>
            </a>
          @endforeach
        @endif
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection
