@extends('app')
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
  		$('#items table').DataTable({"order": [[ 5, "desc" ]],columnDefs: [{ targets: [6],orderable: false}]});
  		$('#orders table').DataTable({"order": [[ 0, "desc" ]]});
  		$('#history table').DataTable({"order": [[ 0, "desc" ]],columnDefs: [{ targets: [7],orderable: false}]});
  		$('[data-toggle="popover"]').popover({html: true});
	});
	function order_information(id){
	  $('#order-info-title').html('Order Information');
	  $('#order-info-body').html('Loading Order Information <i class="fa fa-spinner fa-pulse"></i>');
	  $.post('{{ url("order/modal") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
	    $('#order-info-body').html(data);
	  });
	}
</script>
@endsection
@section('content')
<div class="container-fluid main-container no-padding">
	<div class="col-xs-12 main-col">
		<h1>Item/Order History for User: {{ $user->name }}</h1>
		<div id="admin_tab_panel" role="tabpanel">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Frequently Ordered Items</a></li>
				<li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">All Orders</a></li>
				<li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">Item History</a></li>
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
								<th>Picture</th>
							</tr>
						</thead>
						<tbody>
							@foreach($user->frequent_products['products'] as $product)
								<tr>
									<td>{{ $product->item_number }}</td>
									<td>{{ $product->name }}</td>
									<td>{{ $product->manufacturer }}</td>
									<td>{{ $product->price_string }}</td>
									<td>{{ $product->msrp_string }}</td>
									<td>{{ $user->frequent_products['quantities'][$product->id] }}</td>
									<td>
										<a href="javascript:void(0);" class="btn btn-link" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<img src='{{ asset('/pictures/'.$product->picture) }}' class='img-responsive center-block' />"> 
											<img src='{{ asset('/pictures/'.$product->picture) }}' class='img-responsive center-block'  style="max-height:40px;"/>
										</a>
									</td>
								</tr>
							@endforeach
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
							        <td>{{ $detail->product->item_number }}</td>
							        <td>{{ $detail->product->name }}</td>
							        <td>{{ $detail->product->manufacturer }}</td>
							        <td>{{ $detail->product->price_string }}</td>
							        <td>{{ $detail->product->msrp_string }}</td>
							        <td>{{ $detail->quantity }}</td>
									<td>
										<a href="javascript:void(0);" class="btn btn-link" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<img src='{{ asset('/pictures/'.$product->picture) }}' class='img-responsive center-block' />"> 
											<img src='{{ asset('/pictures/'.$product->picture) }}' class='img-responsive center-block'  style="max-height:40px;"/>
										</a>
									</td>
							      </tr>
							    @endforeach
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('modals')
	@include('admin.modals.order-info')
@stop