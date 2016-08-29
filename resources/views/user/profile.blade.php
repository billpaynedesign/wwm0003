@extends('app')
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#shipping_table').DataTable();
	});
	function user_form_validate(){
		if($('#password').val()){
			if(!$('#password_confirmation').val()){
				$('#password').addClass('has-error');
				$('#password_confirmation').addClass('has-error');
				alert('If you would like to change the password please be sure to fill out the confirmation as well. Or you can leave the password field blank to not change.');
				return false;
			}
			else if($('#password').val() !== $('#password_confirmation').val()){
				$('#password').addClass('has-error');
				$('#password_confirmation').addClass('has-error');
				alert('The password confirmation does not match with the password you entered please try again to continue.');
				return false;
			}
			else{
				$('#password').removeClass('has-error');
				$('#password_confirmation').removeClass('has-error');
				return true;
			}
		}
		else{
			$('#password').removeClass('has-error');
			$('#password_confirmation').removeClass('has-error');
			return true;
		}
	}
</script>
@stop
@section('content')
<div class="container main-container no-padding">
	<div class="col-xs-12 main-col">
	<h1>Your Profile Information</h1>
		<form action="{{ route('user-profile-update') }}" method="post" onsubmit="return user_form_validate();">
			<div class="form-group">
				<label for="company">Organization Name</label>
				<input type="text" name="company" id="company" class="form-control" value="{{ $user->company }}" />
			</div>
			<div class="form-group">
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}" />
			</div>
			<div class="form-group">
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}" />
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" class="form-control" value="{{ $user->email }}" required />
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" name="password" id="password" class="form-control" />
				<p class="help-block">Leave blank to stay the same</p>
			</div>
			<div class="form-group">
				<label for="password_confirmation">Confirm Password</label>
				<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" />
			</div>
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}" />
			</div>
			<div class="form-group">
				<label for="secondary_phone">Alternate Phone</label>
				<input type="text" name="secondary_phone" id="secondary_phone" class="form-control" value="{{ $user->secondary_phone }}" />
			</div>
			<div class="form-group">
				<a href="{{ route('shipto.create') }}" class="btn btn-success"><span class="fa fa-plus"></span>&nbsp;Add Shipping Address</a>
				<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
			</div>
		</form>
			@if(count($user->shipping)>0)
				<div class="form-group">
					<h2>Shipping:</h2>
  					<table id="shipping_table" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th>Shipping ID</th>
								<th>Name</th>
								<th>Address</th>
								<th>City</th>
								<th>State</th>
								<th>Zip</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($user->shipping as $s)
								<tr>
									<td>{{ $s->id_string }}</td>
									<td>{{ $s->name }}</td>
									<td>{{ $s->address1.' '.$s->address2 }}</td>
									<td>{{ $s->city }}</td>
									<td>{{ $s->state }}</td>
									<td>{{ $s->zip }}</td>
									<td>
										<a href="{{ route('shipto.edit', $s->id) }}" class="btn btn-warning"><span class="fa fa-edit"></span></a>
										<form action="{{ route('shipto.destroy', $s->id) }}" method="POST" role="delete" onsubmit="return confirm('Are you sure you want to delete this address?');" style="display: inline-block;">
											{!! csrf_field() !!}
											<input type="hidden" name="_method" value="DELETE" />
											<button type="submit" class="btn btn-danger"><span class="fa fa-trash"></span></button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endif
	</div>
</div>
  	@include('partial.sidebar-contact-full')
@stop