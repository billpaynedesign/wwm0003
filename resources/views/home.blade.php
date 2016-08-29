@extends('app')
@section('content')
  <div class="container index-container">
    <h1>Featured Categories: </h1>
    <div class="featured-holder">
      @if($featured)
        @foreach($featured as $f)
          <a href="{{ route('category-show', $f->slug) }}" title="{{ $f->name }}" class="featured-item">
            <img src="{{ asset($f->picture?'pictures/'.$f->picture:'images/noimg.gif') }}">
            <p>{{ $f->name }}</p>
          </a>
        @endforeach
      @endif
    </div>
  </div>

  @include('partial.sidebar-contact-full')
@endsection