@extends('layout')

@section('title') @parent :: 404 Error @stop

@section('content')

<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>404!</h1>
			<p>Whoops! Looks like something went wrong. <a href="{{ route('home') }}">Click here</a> to go back to the home page.</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</div>
	</div>
</div>
@endsection