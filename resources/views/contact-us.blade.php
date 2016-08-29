@extends('app')
@section('title')Contact Us :: @parent @stop
@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Contact Us</h1>
    <form action="{{ route('contact-us-submit') }}" method="post" role="form">
    	<div class="form-group">
    		<label for="name">Name</label>
    		<input id="name" name="name" class="form-control" value="{{ Auth::check()?Auth::user()->name:'' }}" />
    	</div>
    	<div class="form-group">
    		<label for="phone">Phone</label>
    		<input id="phone" name="phone" class="form-control" value="{{ Auth::check()?Auth::user()->phone:'' }}" />
    	</div>
    	<div class="form-group">
    		<input id="email" name="email" class="form-control hide" />
    		<label for="real_email">Email</label>
    		<input id="real_email" name="real_email" class="form-control" value="{{ Auth::check()?Auth::user()->email:'' }}" />
    	</div>
    	<div class="form-group">
    		<label for="message">Description</label>
    		<textarea id="message" name="message" class="form-control"></textarea>
    	</div>
    	<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Submit</button>
    </form> 
  </div>
</div>
@endsection