@extends('app')

@section('title') @parent :: 404 Error @stop

@section('content')

<div class="container container-main">
	<h1>404!</h1>
	<p>Whoops! Looks like something went wrong. <a href="{{ route('home') }}">Click here</a> to go back to the home page.</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  @include('partial.sidebar-contact')
</div>

@endsection