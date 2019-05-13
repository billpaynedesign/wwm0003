@extends('layout')

@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Add Shipping Address</h1>
			<form action="{{ route('shipto.store') }}" method="POST" role="form">
				<div class="form-group">
					<label for="name">Shipping Name</label>
					<input type="text" name="name" id="name" class="form-control" required/>
				</div>
				<div class="form-group">
					<label for="address1">Address 1</label>
					<input type="text" name="address1" id="address1" class="form-control" required/>
				</div>
				<div class="form-group">
					<label for="address2">Address 2</label>
					<input type="text" name="address2" id="address2" class="form-control" />
				</div>
				<div class="form-group">
					<label for="city">City</label>
					<input type="text" name="city" id="city" class="form-control" required/>
				</div>
				<div class="form-group">
					<label for="state">State</label>
					<select name="state" id="state" class="form-control" required>
						<option value="">-- Select State --</option>
			            @foreach(App\State::all() as $state)
			            <option value="{{ $state->abbr }}">{{ $state->state }}</option>
			            @endforeach
		        	</select>
				</div>
				<div class="form-group">
					<label for="zip">Zip</label>
					<input type="text" name="zip" id="zip" class="form-control" required/>
				</div>
				<div class="form-group">
					<a href="{{ route('user-profile') }}" class="btn btn-cancel">Cancel</a>
					<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
@stop