@extends('layout')

@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Reset Password</h1>
			@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
			@endif

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

			<form role="form" method="POST" action="{{ url('/password/email') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="form-group">
					<label for="email">E-Mail Address</label>
					<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-normal">Send Password Reset Link</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
