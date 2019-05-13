@extends('layout')

@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Edit # {{ $order->invoice_num }}</h1>
			<form action="{{ route('order-update') }}" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="qb_invoice_number">QB Invoice #: </label>
					<input type="text" name="qb_invoice_number" id="qb_invoice_number" class="form-control" value="{{ $order->qb_invoice_number }}">
				</div>
				<div class="form-group">
					<label for="first_name">First Name: </label>
					<input type="text" name="first_name" id="first_name" class="form-control" value="{{ $order->first_name }}">
				</div>
				<div class="form-group">
					<label for="last_name">Last Name: </label>
					<input type="text" name="last_name" id="last_name" class="form-control" value="{{ $order->last_name }}">
				</div>
				<div class="form-group">
					<label for="name">Shipping Name: </label>
					<input type="text" name="name" id="name" class="form-control" value="{{ $order->shippingname }}">
				</div>
				<div class="form-group">
					<label for="address1">Address 1: </label>
					<input type="text" name="address1" id="address1" class="form-control" value="{{ $order->address1 }}">
				</div>
				<div class="form-group">
					<label for="address2">Address 2: </label>
					<input type="text" name="address2" id="address2" class="form-control" value="{{ $order->address2 }}">
				</div>
				<div class="form-group">
					<label for="city">City: </label>
					<input type="text" name="city" id="city" class="form-control" value="{{ $order->city }}">
				</div>
				<div class="form-group">
					<label for="state">State: </label>
			          <select name="state" id="state" class="form-control">
				          @foreach(App\State::all() as $state)
				          <option value="{{ $state->abbr }}" {{ $state->abbr==$order->state?'selected':'' }}>{{ $state->state }}</option>
				          @endforeach
			          </select>
				</div>
				<div class="form-group">
					<label for="zip">Zip: </label>
					<input type="text" name="zip" id="zip" class="form-control" value="{{ $order->zip }}">

					<input type="hidden" name="id" value="{{ $order->id }}">

				</div>

				<div class="form-group table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="text-danger">Delete Item</th>
								<th>Item</th>
								<th>Paid Status</th>
								<th>Ship Status</th>
								<th>QTY</th>
								<th>Backordered</th>
								<th>Price</th>
								<th>Lot #</th>
								<th>Expiration Date</th>
								<th>Tax exempt?</th>
								<th>Split Line</th>
							</tr>
						</thead>
						<tbody>
							@foreach($order->details as $detail)
								<tr>
									<td>
										@if(!$detail->shipped)
											<input type="checkbox" name="item_delete[]" value="{{ $detail->id }}">
										@endif
									</td>
									<td>{{ $detail->product->category?$detail->product->category->name:'Uncategorized' }} - {{ $detail->product->name }}</td>
									<td>{{ $detail->paid?'Paid':'Pending' }}</td>
									<td>{{ $detail->shipped?'Shipped':'Pending' }}</td>
									<td>
										<input type="number" name="item_qty[{{ $detail->id }}]" min="1" value="{{ $detail->quantity }}" class="form-control" />
									</td>
									<td>
										<input type="number" name="backordered[{{ $detail->id }}]" min="0" max="{{ $detail->quantity }}" value="{{ $detail->backordered }}" class="form-control" />
									</td>
						            @if($order->user->product_price_check($detail->product->id))
						              <td>{{ $order->user->product_price_check($detail->product->id)->price_string }}</td>
						            @else
						              <td>{{ $detail->product->min_price_string }}</td>
						            @endif
									<td>
										@if(!$detail->shipped)
											<input type="text" name="lot_number[{{ $detail->id }}]" class="form-control" value="{{ $detail->lot_number }}" />
										@else
											{{ $detail->lot_number }}
										@endif
									</td>
									<td>
										@if(!$detail->shipped)
											<input type="text" name="expiration[{{ $detail->id }}]" class="form-control datepicker" value="{{ $detail->expiration }}" />
										@else
											{{ $detail->expiration }}
										@endif
									</td>
									<td class="text-center">
										@if(!$detail->shipped)
											<input type="checkbox" name="tax_exempt[{{ $detail->id }}]" {{ $detail->taxable?'':'checked' }} value="1" />
										@endif
									</td>
									<td>
										@if(!$detail->shipped)
											<a href="{{ route('order-edit-line',$detail->id) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span></a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<a class="btn btn-success" data-toggle="modal" href="#AddItemModal"><span class="fa fa-plus"></span>&nbsp;Add Item</a>
				<button type="submit" name="cancel" value="true" class="btn">Cancel</button>
				<button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
</div>
@endsection



@section('modals')
<div class="modal fade" id="AddItemModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('order-product-add', $order->id) }}" method="POST" role="form">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select id="order-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
					</div>
					<div class="form-group">
						<select id="order-add-uom" name="uom_id" class="form-control" style="display:none;"></select>
					</div>
					<div class="form-group">
						<input id="order-add-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control" style="display:none;">
					</div>
					<input type="hidden" id="add_product_id" name="product_id" value="" />
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
	            	item.name = item.name+' - #'+item.item_number;
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
            onChange: function(value){
	            $("#add_product_id").val(this.items[0]);

				$.get('{{ route('api-get-uom-product-options-html') }}', {product_id: this.items[0]}, function(data){
					console.log(data);
               		$("#order-add-uom").show().html(data);
               		$("#order-add-quantity").show();
				});
            }
        });
	});
</script>
@endsection