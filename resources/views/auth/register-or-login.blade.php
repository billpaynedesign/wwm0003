@extends('layout')

@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Register / Login</h1>
			<div class="col-md-6 col-xs-12" style="border-right: 1px solid #ccc;">
				<p>If you are new user then please register here:</p>
				<form role="form" method="POST" action="{{ url('/auth/register') }}">
					<div class="form-group">
						<label for="company">Organization Name</label>
						<input type="text" class="form-control" name="company" id="company" value="{{ old('company') }}">
						<label for="first_name">First Name</label>
						<input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name') }}">
						<label for="last_name">Last Name</label>
						<input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name') }}">
						<label for="email">E-Mail Address</label>
						<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
						<label for="password">Password</label>
						<input type="password" class="form-control"  name="password" id="password">
						<label for="password_confirmation">Confirm Password</label>
						<input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
					</div>
					<button type="submit" class="btn btn-default" name="_token" value="{{ csrf_token() }}">Register</button>
				</form>
			</div>
			<div class="col-md-6 col-xs-12 text-left">
				<h4>Already registered? Login!</h4>
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Whoops!</strong> There were some problems with your input.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif

				<form role="form" method="POST" action="{{ url('/auth/login') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="form-group">
						<label for="email">E-Mail Address</label>
						<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
						<label for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password">
					</div>

					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember"> Remember Me
							</label>
						</div>
						<button type="submit" class="btn btn-default">Login</button>

						<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
