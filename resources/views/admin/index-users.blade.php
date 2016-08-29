<div class="table-responsive">
	<table class="table table-striped table-hover tablesorter">
		<thead>
	      <tr>
	        <th>User Name</th>
	        <th>Email</th>
	        <th>Shipping Name</th>
	        <th>Address</th>
	        <th>City</th>
	        <th>State</th>
	        <th>Zip</th>
	        <th>Phone</th>
	        <th>Admin?</th>
	        <th>Date Created</th>
	        <th>Actions</th>
	      </tr>
	    </thead>
	    <tbody>
	    	@foreach($users as $user)
	    	<tr>
	        	<td>{{ $user->name }}</td>
	        	<td>{{ $user->email }}</td>
	        	<td>{{ $user->shippingname }}</td>
	        	<td>{{ $user->address1.' '.$user->address2 }}</td>
	        	<td>{{ $user->city }}</td>
	        	<td>{{ $user->state }}</td>
	        	<td>{{ $user->zip }}</td>
	        	<td>{{ $user->phone }}</td>
	        	<td>{{ $user->admin?'yes':'' }}</td>
	        	<td>{{ $user->created_at->format('m-d-Y') }}</td>
	        	<td>
				    <a href="{{ route('user-info',$user->id) }}" class="btn btn-info" title="Display {{ $user->name }} item/order history information">
				       	<span class="fa fa-th-list" aria-hidden="true"></span>
				    </a>

				    <a href="{{ route('user-edit',$user->id) }}" class="btn btn-warning" title="Edit {{ $user->name }} information">
				       	<span class="fa fa-edit" aria-hidden="true"></span>
				    </a>

				    <a href="{{ route('user-product', $user->id) }}" class="btn btn-success" title="Add/Edit product pricing for {{ $user->name }}">
				    	<span class="fa fa-usd"></span>
				    </a>
	        		<a href="{{ route('user-delete',$user->id) }}" class="btn btn-danger" title="Remove {{ $user->name }}" onclick="return confirm('Are you sure you want to remove the user: {{ $user->name }}');">
			            <span class="fa fa-trash" aria-hidden="true"></span>
			        </a>

	        	</td>
	    	</tr>
	    	@endforeach
	    </tbody>
	</table>
</div>