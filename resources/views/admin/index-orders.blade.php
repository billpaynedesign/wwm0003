@extends('app')


@section('title') Admin Dashboard :: @parent @stop

@section('scripts')
<script type="text/javascript">
Dropzone.autoDiscover = false;
$(document).ready(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });
  $('.html-popover').popover({html:true});
  $('#orders_table').DataTable({"order": [[ 0, "desc" ]]});
  @if(session()->has('order-status-failed'))
  order_status({{ session()->get('order-status-failed') }});
  $('#order-status').modal('show')
  @endif
});
function order_status(id){
  $.post('{{ route("order-status") }}',{id:id},function(data){
    //console.log(data);
    $('#order-status .modal-body').html(data);
    $('.orderstatus-datepicker').datepicker();
  });
}
function order_toggle_backordered(id){
  $.get('{{ route("order-toggle-backordered",'') }}/'+id,{},function(data){
    var html = (data.response == 1)?'<span class="text-success glyphicon glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove"></span>';
    $('#order-backordered-'+data.id).html(html);
  });
}
function order_information(id){
  $('#order-info-title').html('Order Information');
  $('#order-info-body').html('Loading Order Information <i class="fa fa-spinner fa-pulse"></i>');
  $.post('{{ url("order/modal") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
    $('#order-info-body').html(data);
  });
}
</script>
@stop

@section('content')
<div class="container-fluid main-container no-padding">
  <div class="col-xs-12 main-col">
    <div class="page-header">
      <h1 id="dashboard">Admin Dashboard</h1>
    </div>
    <div id="admin_tab_panel" role="tabpanel">

      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="{{ route('admin-dashboard') }}#dashboard">Home</a></li>
        <li role="presentation"><a href="{{ route('admin-categories') }}#dashboard">Categories</a></li>
        <li role="presentation"><a href="{{ route('admin-products') }}#dashboard">Products</a></li>
        <li role="presentation"><a href="{{ route('admin-options') }}#dashboard">Product Options</a></li>
        <li role="presentation" class="active"><a href="{{ route('admin-orders') }}#dashboard">Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-backorders') }}#dashboard">Back Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-users') }}#dashboard">Users</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane tab-pane-admin active">
          <!--
          <div class="form-group">
            <a href="{{ route('order-print-backordered') }}" target="_blank" class="btn btn-info" >Print Backorder&nbsp;&nbsp;<span class="glyphicon glyphicon-print"></span></a>
          </div>
          -->
          <div class="table-responsive text-left">
            <table id="orders_table" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Order Date</th>
                  <th>Invoice ID</th>
                  <th>Ship Status</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>Phone #</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($orders)
                @foreach($orders as $order)
                <tr id="{{ $order->id }}">
                  <td>{{ $order->created_at->format('m-d-Y H:i:s') }}</td>
                  <td>{{ $order->invoice_num }}</td>
                  <td>{{ $order->shipstatus }}</td>
                  <td>{{ $order->shippingname }}</td>
                  <td>{{ $order->address1.' '.$order->address2.' '.$order->city.', '.$order->state.' '.$order->zip }}</td>
                  <td>{{ $order->phone.' '.($order->secondary_phone?'or '.$order->secondary_phone:'') }}</td>
                  <td>
                    <button class="btn btn-info" data-toggle="modal" data-target="#order-info" title="Order Information" onclick="order_information('{{ $order->id }}')">
                      <span class="fa fa-info"></span>
                    </button>
                    <button class="btn btn-success" title="Edit Status for #{{ $order->id }}" data-toggle="modal" data-target="#order-status" onclick="order_status('{{ $order->id }}')"> 
                      <span class="fa fa-truck" aria-hidden="true"></span>
                    </button>
                    <a href="{{ route('order-edit',$order->id) }}" class="btn btn-warning" title="Edit Shipping/Items for #{{ $order->id }}">
                      <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('order-delete',$order->id) }}" class="btn btn-danger" title="Remove #{{ $order->id }}" onclick="return confirm('Are you sure you want to remove order: #{{ $order->id }}');">
                      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </a>
                    @if(!$order->qb_id)
                      <a class="btn btn-darkblue" href="{{ route('order-create-qb-invoice', [$order->id, 'quickbooks1']) }}" title="Create quickbooks invoice for World Wide Medical Distributors,Inc." onclick="return confirm('Are you sure you want to create a quickbooks invoice for World Wide Medical Distributors,Inc.');">WWM</a>
                      <a class="btn btn-purple" href="{{ route('order-create-qb-invoice', [$order->id, 'quickbooks2']) }}" title="Create quickbooks invoice for PRESTIGE MEDICAL DEVICES, INC." onclick="return confirm('Are you sure you want to create a quickbooks invoice for PRESTIGE MEDICAL DEVICES, INC.');">PMD</a>
                    @endif
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<hr/>
@stop






@section('modals')
@include('admin.modals.order-info')

<div class="modal fade" id="order-status" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="order_status_title">Order Status</h4>
        @if(session()->has('order-status-failed'))
        <div class="alert alert-danger">{{ session()->get('fail') }}</div>
        @endif
      </div>
      <form action="{{ route('order-status-update') }}" method="post">
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal" onsubmit="return false;">Close</button>
          <button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-default">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop
