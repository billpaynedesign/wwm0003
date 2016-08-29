@extends('app')

@section('content')
<div class="container-fluid">
	<div class="container container-main">
		<h1>Reset Password</h1>
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

		<form role="form" method="POST" action="{{ url('/password/reset') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="token" value="{{ $token }}">

			<div class="form-group">
				<label for="email">E-Mail Address</label>
				<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
				<label for="password">Password</label>
				<input type="password" class="form-control" name="password" id="password">
				<label for="password_confirmation">Confirm Password</label>
				<input type="password" class="form-control" name="password_confirmation" id="password_confirmation">	
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-normal">Reset Password</button>
			</div>
		</form>
	</div>
</div>
@endsection
