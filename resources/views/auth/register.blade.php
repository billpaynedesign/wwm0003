@extends('app')

@section('content')
<div class="container main-container no-padding">
	<div class="col-md-8 col-xs-12 main-col">
		<h1>Register</h1>
		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<strong>Whoops!</strong> There were some problems with your input.<br/><br/>
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		<form role="form" method="POST" action="{{ url('/auth/register') }}">
			<div class="form-group">
				<label for="company">Organization Name</label>
				<input type="text" class="form-control" name="company" id="company" value="{{ old('company') }}">
			</div>
			<div class="form-group">
				<label for="first_name">First Name</label>
				<input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name') }}">
			</div>
			<div class="form-group">
				<label for="last_name">Last Name</label>
				<input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name') }}">
			</div>
			<div class="form-group">
				<label for="email">E-Mail Address</label>
				<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control"  name="password" id="password">
			</div>
			<div class="form-group">
				<label for="password_confirmation">Confirm Password</label>
				<input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
			</div>
			<button type="submit" class="btn btn-default" name="_token" value="{{ csrf_token() }}">Register</button>
		</form>
	</div>
  @include('partial.sidebar-contact-col4')
</div>
@endsection
