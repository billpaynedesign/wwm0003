@extends('layout')

@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Edit Shipping Address</h1>
			<form action="{{ route('shipto.update', $shipto->id) }}" method="POST" role="form">
				<div class="form-group">
					<label for="name">Shipping Name</label>
					<input type="text" name="name" id="name" class="form-control" value="{{ $shipto->name }}"  required/>
				</div>
				<div class="form-group">
					<label for="address1">Address 1</label>
					<input type="text" name="address1" id="address1" class="form-control" value="{{ $shipto->address1 }}"  required/>
				</div>
				<div class="form-group">
					<label for="address2">Address 2</label>
					<input type="text" name="address2" id="address2" class="form-control" value="{{ $shipto->address2 }}" />
				</div>
				<div class="form-group">
					<label for="city">City</label>
					<input type="text" name="city" id="city" class="form-control" value="{{ $shipto->city }}"  required/>
				</div>
				<div class="form-group">
					<label for="state">State</label>
					<select name="state" id="state" class="form-control" required>
						<option value="">-- Select State --</option>
			            @foreach(App\State::all() as $state)
			            <option value="{{ $state->abbr }}" {{ ($state->abbr==$shipto->state)?'selected':'' }}>{{ $state->state }}</option>
			            @endforeach
		        	</select>
				</div>
				<div class="form-group">
					<label for="zip">Zip</label>
					<input type="text" name="zip" id="zip" class="form-control" value="{{ $shipto->zip }}"  required/>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<a href="{{ route('user-profile') }}" class="btn btn-cancel">Cancel</a>
				<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
			</form>
		</div>
	</div>
</div>
@stop