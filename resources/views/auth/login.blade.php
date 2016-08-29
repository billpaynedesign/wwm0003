@extends('app')

@section('content')
<div class="container main-container no-padding">
	<div class="col-md-8 col-xs-12 main-col">
		<h1>Login</h1>
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
		<p>Don't have an account yet? <a href="{{ url('auth/register') }}">Register here</a></p>
	</div>
	@include('partial.sidebar-contact-col4')
</div>

@endsection
