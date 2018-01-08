@extends('layout')

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('.datepicker').datepicker();
		$('#order-edit-search').selectize({
	        valueField: 'url',
	        labelField: 'name',
            searchField: ['name','item_number'],
	        maxOptions: 1000,
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
	            {value: 'product', label: 'Products'}
	        ],
	        optgroupField: 'class',
	        optgroupOrder: ['product'],
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
				$.get('{{ route('api-get-uom-product-options-html') }}', {product_id: this.items[0]}, function(data){
               		$("#add_uom_id").show().html(data);
				});
	        }
	    });

		$('#pricingTable').DataTable();
	});
</script>
@endsection
@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Edit Product Price for User: {{ $user->name }}</h1>
			<form action="{{ route('user-product-submit', $user->id) }}" method="post" enctype="multipart/form-data">

				<div class="form-group table-responsive">
					<table id="pricingTable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Product</th>
								<th>Price</th>
								<th>Item #</th>
								<th>Custom Price</th>
								<th>Custom Item #</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							@if(count($user->product_price)>0)
								@foreach($user->product_price as $userpricing)
									@if($userpricing->product && $userpricing->uom)
										<tr>
											<td>{{ $userpricing->product->name }} - {{ $userpricing->uom->name }}</td>
											<td>{{ $userpricing->uom->price_string }}</td>
											<td>{{ $userpricing->product->item_number }}</td>
											<td>
												<input type="number" step="0.01" min="0" name="prices[{{ $userpricing->id }}]" value="{{ $userpricing->price?$userpricing->price:$userpricing->product->price }}" class="form-control">
											</td>
											<td>
												<input type="text" name="skus[{{ $userpricing->id }}]" value="{{ $userpricing->custom_sku?:'' }}" class="form-control">
											</td>
											<td class="text-center"><input type="checkbox" name="delete[]" value="{{ $userpricing->id }}" /></td>
										</tr>
									@endif
								@endforeach
							@else
								<tr>
									<td colspan="4">No custom pricing has been added for this user. Click the Add Item button below to get started.</td> 
								</tr>
							@endif
						</tbody>
					</table>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<a class="btn btn-success" data-toggle="modal" href="#AddItemModal"><span class="fa fa-plus"></span>&nbsp;Add Item</a>
				<button type="submit" name="cancel" value="true" class="btn btn-cancel">Cancel</button>
				<button type="submit" name="submit" value="true" class="btn btn-primary">Save</button>
			</form>
		</div>
	</div>
</div>
@endsection



@section('modals')
<div class="modal fade" id="AddItemModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('user-product-add', $user->id) }}" method="POST" role="form">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden" id="add_product_id" name="product_id" value="" />
						<select id="order-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
					</div>
					<div class="form-group">
						<select id="add_uom_id" name="uom_id" class="form-control" style="display:none;"></select>
					</div>

				</div>
				<div class="modal-footer">
					{!! csrf_field() !!}
					<button type="submit" class="btn btn-primary">Save changes</button>
					<a class="btn btn-cancel" data-dismiss="modal">Close</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
