@extends('app')

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('.datepicker').datepicker();
		$('#order-edit-search').selectize({
	        valueField: 'url',
	        labelField: 'name',
	        searchField: ['name'],
	        maxOptions: 10,
	        options: [],
	        create: false,
	        render: {
	            option: function(item, escape) {
	            	var picturespath  = '{{ asset("/pictures") }}/';
	            	var noimage = '{{ asset("/images") }}/noimg.gif';
	            	if(item.picture){
	            		var picture = picturespath+item.picture;
	            	}
	            	else{
	            		var picture = noimage;
	            	}
	                return '<div><img src="'+picture+'" style="max-width:50px; max-height: 50px; margin-right:5px;">' +item.name+'</div>';
	            }
	        },
	        optgroups: [
	            {value: 'product', label: 'Products'},
	            {value: 'item_number', label: 'Item-Num'}
	        ],
	        optgroupField: 'class',
	        optgroupOrder: ['product','item_number'],
	        load: function(query, callback) {
	            if (!query.length) return callback();
	            $.ajax({
	                url: root+'/api/product/add/search',
	                type: 'GET',
	                dataType: 'json',
	                data: {
	                    q: query
	                },
	                error: function() {
	                    callback();
	                },
	                success: function(res) {
	                    callback(res.data);
	                }
	            });
	        },
	        onChange: function(){
	            $("#add_product_id").val(this.items);
	        }
	    });
	});
</script>
@endsection
@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
	<h1>Edit Product Price {{ $user->name }}</h1>
		<form action="{{ route('user-product-submit', $user->id) }}" method="post" enctype="multipart/form-data">

			<div class="form-group table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Product</th>
							<th>Original Price</th>
							<th>Custom Price</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						@if(count($user->product_price)>0)
							@foreach($user->product_price as $userpricing)
								<tr>
									<td>{{ $userpricing->product->name }}</td>
									<td>{{ $userpricing->product->price_string }}</td>
									<td><input type="number" name="prices[{{ $userpricing->id }}]" value="{{ $userpricing->price?$userpricing->price:$userpricing->product->price }}"</td>
									<td><input type="checkbox" name="delete[]" value="{{ $userpricing->id }}" /></td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="4">No custom pricing has been added for this user. Click the Add Item button below to get started</td> 
							</tr>
						@endif
					</tbody>
				</table>
			</div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<a class="btn btn-success" data-toggle="modal" href="#AddItemModal"><span class="fa fa-plus"></span>&nbsp;Add Item</a>
			<button type="submit" name="cancel" value="true" class="btn">Cancel</button>
			<button type="submit" name="submit" value="true" class="btn btn-default">Save</button>
		</form>
	</div>
</div>
<div class="modal fade" id="AddItemModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('user-product-add', $user->id) }}" method="POST" role="form">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
				</div>
				<div class="modal-body">
					<input type="hidden" id="add_product_id" name="product_id" value="" />
					<select id="order-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
				</div>
				<div class="modal-footer">
					{!! csrf_field() !!}
					<button type="submit" class="btn btn-primary">Save changes</button>
					<a class="btn" data-dismiss="modal">Close</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
