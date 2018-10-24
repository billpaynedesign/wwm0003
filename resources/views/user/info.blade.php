@extends('layout')
@section('scripts')
<script type="text/javascript">
  Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
    num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
  };
	var cart_id = {{ $cart_id?$cart_id:0 }};
	var token = '{{ csrf_token() }}';
	$(document).ready(function(){
        $('#items table').DataTable({
          searchDelay: 500,
          serverSide: true,
          ajax: '{{ route('user-info-api',$user->id) }}',
          stateSave: true,
          stateDuration: 1800,
          "order": [[ 5, "desc" ]],
          "columns": [
            {
              "data": "item_number",
              "name": "item_number"
            },
            {
              "data": "name",
              "name": "name"
            },
            {
              "data": "manufacturer",
              "name": "manufacturer"
            },
            {
              "data": "price_string",
              "name": "price_string"
            },
            {
              "data": "msrp_string",
              "name": "msrp_string"
            },
            {
              "data": "total_purchased",
              "name": "total_purchased"
            }
          ]

        });
  		$('#orders table').DataTable({"order": [[ 0, "desc" ]]});
  		$('#history table').DataTable({"order": [[ 0, "desc" ]],columnDefs: [{ targets: [7],orderable: false}]});
  		$('[data-toggle="popover"]').popover({html: true});
  		var cartAddModal = $('#AddCartItem'),
			selectedItem;

  		@if(array_key_exists('tab',$_GET))
  			$('#admin_tab_panel a[href="#{{$_GET['tab']}}"]').tab('show');
        @endif


        $('#cart-edit-search').selectize({
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
                $("#cart-add-product").val(this.items[0]);

				$.get('{{ route('api-get-uom-product-options-html') }}', {product_id: this.items[0]}, function(data){
               		$("#cart-add-uom").show().html(data);
               		$("#cart-add-quantity").show();
				});
            }
        });

		$('#addToCart').click(function(){

            var product_id = $('#cart-add-product').val();
			var uom_id = $('#cart-add-uom').find(':selected').val();
			var quantity = $('#cart-add-quantity').val();

		    $.post('{{ route('user-cart-add') }}', {
                    cart_id: cart_id,
                    product_id: product_id,
                    uom_id: uom_id,
                    quantity: quantity,
                    _token:  token
				}, function(data){
					token = data['token'];
				    $('#cart table tbody').html(data['html']);
				    $('#cart-add-product').val('')
					$('#cart-add-uom').hide().html('');
					$('#cart-add-quantity').hide().val('1');
					get_total();
				}
			);
		});
	});
	function order_information(id){
	  $('#order-info-title').html('Order Information');
	  $('#order-info-body').html('Loading Order Information <i class="fa fa-spinner fa-pulse"></i>');
	  $.post('{{ url("order/modal") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
	    $('#order-info-body').html(data);
	  });
	}
	function new_cart(){
		$.post('{{ route('user-new-cart') }}', {user_id: {{ $user->id }}, _token:  token}, function(data){
			cart_id = data['new_id'];
			token = data['token'];
			$('#cart table tbody tr td').html('No items in cart yet.');
			$('#new_cart_button').hide();
			$('#add_cart_button').removeAttr('disabled').removeClass('disabled');
			get_total();
		});
	}
	function delete_item(id){
		if(confirm('Are you sure you want to remove this item from the cart?')){
			$.post('{{ route('user-cart-remove') }}',{
				item_id: id,
				_token: token
			},function(data){
				token = data['token'];
				$('#cart table tbody').html(data['html']);
				get_total();
			});
		}
	}
    function edit(id){
      $('#'+id).removeClass('hide');
      $('#edit_button_'+id).addClass('hide');
    }
    function update_item(id){
    	var quantity = $('#quantity-'+id).val();
		$.post('{{ route('user-cart-update') }}',{
			item_id: id,
			quantity: quantity,
			_token: token
		},function(data){
			token = data['token'];
			$('#cart table tbody').html(data['html']);
			get_total();
		});
    }
    function get_total(){
    	$.get('{{ route('cart-get-total') }}',{
    		cart_id: cart_id
    	},function(data){
    		data = Number(data);
    		if(data>0){
    			if($('#cart_checkout_button').length==0){
    				var route = '{{ url('cart/checkout/shipping') }}/'+cart_id+'/admin';
    				$('#cart').append('<a id="cart_checkout_button" href="'+route+'" class="btn btn-primary pull-right">Checkout</a>');
    			}
    			else{
    				$('#cart_checkout_button').remove();
    			}
    		}
    		else{
    			$('#cart_checkout_button').remove();
    		}
    		$('#cart_total').html('$'+data.format(2, 3, ',', '.'));
    	});
    }
</script>
@endsection
@section('content')
<div id="row-main" class="row">
  	<div id="container-main" class="container">
  		<div id="col-main" class="col-xs-12">
			<h1>Item/Order History for User: {{ $user->name }}</h1>
			<div id="admin_tab_panel" role="tabpanel">

				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Frequently Ordered Items</a></li>
					<li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">All Orders</a></li>
					<li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">Item History</a></li>
					<li role="presentation"><a href="#cart" aria-controls="cart" role="tab" data-toggle="tab">Cart</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<!-- items -->
					<div role="tabpanel" class="tab-pane tab-pane-admin active" id="items">
						<table class="table table-bordered table-striped table-hover table-sorter">
							<thead>
								<tr>
									<th>Item #</th>
									<th>Name</th>
									<th>Manufacturer</th>
									<th>Price</th>
									<th>MSRP</th>
									<th>Total Purchased</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- orders -->
					<div role="tabpanel" class="tab-pane tab-pane-admin" id="orders">
						<table class="table table-bordered table-striped table-hover table-sorter">
							<thead>
								<tr>
							        <th>Order Date</th>
							        <th>ID</th>
							        <th>Ship Status</th>
							        <th>Name</th>
							        <th>Address</th>
							        <th>Phone #</th>
							        <th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($user->orders as $order)
								      <tr id="{{ $order->id }}">
								        <td>{{ $order->created_at->format('m-d-Y H:i:s') }}</td>
								        <td>{{ $order->id }}</td>
								        <td>{{ $order->shipStatus }}</td>
								        <td>{{ $order->shippingname }}</td>
								        <td>{{ $order->address1.' '.$order->address2.' '.$order->city.', '.$order->state.' '.$order->zip }}</td>
								        <td>{{ $order->phone.' '.($order->secondary_phone?'or '.$order->secondary_phone:'') }}</td>
								        <td>
								          <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#order-info" title="Order Information" onclick="order_information('{{ $order->id }}')">
								            <span class="fa fa-info"></span>
								          </button>
								          <button class="btn btn-sm btn-success" title="Edit Status for #{{ $order->id }}" data-toggle="modal" data-target="#order-status" onclick="order_status('{{ $order->id }}')">
								            <span class="fa fa-truck" aria-hidden="true"></span>
								          </button>
								          <a href="{{ route('order-edit',$order->id) }}" class="btn btn-sm btn-warning" title="Edit Shipping/Items for #{{ $order->id }}">
								            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								          </a>
								          <a href="{{ route('order-delete',$order->id) }}" class="btn btn-sm btn-danger" title="Remove #{{ $order->id }}" onclick="return confirm('Are you sure you want to remove order: #{{ $order->id }}');">
								            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
								          </a>

								        </td>
								      </tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<!-- history -->
					<div role="tabpanel" class="tab-pane tab-pane-admin" id="history">
						<table class="table table-bordered table-striped table-hover table-sorter">
							<thead>
								<tr>
							        <th>Ordered Date</th>
							        <th>Item #</th>
							        <th>Name</th>
							        <th>Manufacturer</th>
							        <th>Price</th>
							        <th>MSRP</th>
							        <th>Purchased</th>
							        <th>Picture</th>
								</tr>
							</thead>
							<tbody>
								@foreach($user->orders as $order)
									@foreach($order->details as $detail)
								      <tr>
								        <td>{{ $order->created_at->format('m-d-Y H:i:s') }}</td>
								        <td>{{ $detail->product?$detail->product->item_number:'' }}</td>
								        <td>{{ $detail->product?$detail->product->name:'' }}</td>
								        <td>{{ $detail->product?$detail->product->manufacturer:'' }}</td>
								        <td>{{ $detail->product?$detail->product->price_string:'' }}</td>
								        <td>{{ $detail->product?$detail->product->msrp_string:'' }}</td>
								        <td>{{ $detail->quantity }}</td>
										<td>
                                            @if($detail->product)
											<a href="javascript:void(0);" class="btn btn-link" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<img src='{{ asset('/pictures/'.$detail->product->picture) }}' class='img-responsive center-block' />">
												<img src='{{ asset('/pictures/'.$detail->product->picture) }}' class='img-responsive center-block'  style="max-height:40px;"/>
											</a>
                                            @endif
										</td>
								      </tr>
								    @endforeach
								@endforeach
							</tbody>
						</table>
					</div>
					<!-- cart -->
					<div role="tabpanel" class="tab-pane tab-pane-admin" id="cart">
						<table class="table table-hover table-striped table-bordered text-left">
							<thead>
							<tr>
								<th>Item Name</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Options</th>
								<th>Item Total</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							@if(count($cart)>0)
								@include('user.part.cart_content',['cart'=>$cart])
							@elseif($cart_id)
								<tr><td colspan="6">No items in cart yet.</td></tr>
							@else
								<tr><td colspan="6">No Cart to edit. <a  onclick="new_cart();">Click here</a> to start a cart for {{ $user->name }}</td></tr>
							@endif
							</tbody>
							<tfoot>
							<tr>
								<th colspan="4"><strong>Total</strong></th>
								<th id="cart_total">${{ \number_format($total,2) }}</th>
							</tr>
							</tfoot>
						</table>
						<a id="add_cart_button" class="btn btn-success {{ $cart_id?'':'disabled' }}" data-toggle="modal" href="#AddCartItem" {{ $cart_id?'':'disabled' }}><span class="fa fa-plus"></span>&nbsp;Add Item</a>
						@if(!$cart_id)
							<button id="new_cart_button" class="btn btn-primary" onclick="new_cart();"><span class="fa fa-cart-plus"></span> Create a cart for {{ $user->name }}</button>
						@elseif($total>0)
							<a href="{{ route('admin-cart-shipping',$cart_id) }}" class="btn btn-primary pull-right">Checkout</a>
						@endif
					</div>
					<div class="modal fade" id="AddCartItem">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<a class="close" data-dismiss="modal">&times;</a>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<select id="cart-edit-search" name="q" placeholder="Search Keyword or Item #" class="form-control" required></select>
									</div>
									<div class="form-group">
										<select id="cart-add-uom" name="uom_id" class="form-control" style="display:none;"></select>
									</div>
									<div class="form-group">
										<input id="cart-add-quantity" name="quantity" value="1" type="number" min="1" step="1" class="form-control" style="display:none;">
									</div>
									<input type="hidden" id="cart-add-product" name="product_id" value="" />
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-primary" id="addToCart">Save changes</button>
									<a class="btn btn-cancel" data-dismiss="modal">Close</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('modals')
	@include('admin.modals.order-info')
@stop
