@extends('app')
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
	$('.datepicker').datepicker({
		dateFormat: "mm/yy",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		onClose: function(dateText, inst) {
			function isDonePressed(){
				return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
			}
			if(isDonePressed()){
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
				$('.date-picker').focusout()
			}
		},
		beforeShow : function(input, inst) {

			inst.dpDiv.addClass('month_year_datepicker')

			if ((datestr = $(this).val()).length > 0) {
				year = datestr.substring(datestr.length-4, datestr.length);
				month = datestr.substring(0, 2);
				$(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
				$(this).datepicker('setDate', new Date(year, month-1, 1));
				$(".ui-datepicker-calendar").hide();
			}
		}
	});
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
<style type="text/css">
.ui-datepicker-calendar {
    display: none;
    }
</style>
@endsection
@section('content')
<div class="container main-container no-padding">
	<div class="col-xs-12 main-col">
	<h1>Edit User: {{ $user->name }}</h1>
		<form action="{{ route('user-update') }}" method="post" onsubmit="return user_form_validate();">
			<div class="form-group">
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="admin" value="true" {{ $user->admin?'checked':'' }}> Admin?
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="verified" value="true" {{ $user->verified?'checked':'' }}> Verified?
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="no_pricing" value="true" {{ $user->no_pricing?'checked':'' }}> Hide Pricing?
					</label>
				</div>
			</div>
			<hr/>
			<h3 class="text-blue">License Information</h3>
			<hr/>
			<div class="form-group">
				<label for="account">Account Name</label>
				<input type="text" name="account" id="account" class="form-control" value="{{ $user->account }}" />
			</div>
			<div class="form-group">
				<label for="license_number">License Number</label>
				<input type="text" name="license_number" id="license_number" class="form-control" value="{{ $user->license_number }}" />
			</div>
			<div class="form-group">
				<label for="license_expire">Expiration Date</label>
				<input type="text" name="license_expire" id="license_expire" class="form-control datepicker" value="{{ $user->license_expire }}" />
			</div>
			<hr/>
			<h3 class="text-blue">User Information</h3>
			<hr/>
			<div class="form-group">
				<label for="company">Organization Name</label>
				<input type="text" name="company" id="company" class="form-control" value="{{ $user->company }}" />
			</div>
			<div class="form-group">
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}" required />
			</div>
			<div class="form-group">
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}" required />
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" class="form-control" value="{{ $user->email }}" required />
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" name="password" id="password" class="form-control" />
			</div>
			<div class="form-group">
				<p class="help-block">Leave blank to stay the same</p>
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
				<input type="hidden" name="id" value="{{ $user->id }}" />
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<button type="submit" name="cancel" value="true" class="btn">Cancel</button>
				<button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
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
@endsection