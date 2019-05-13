@extends('layout')


@section('title') Admin Dashboard :: @parent @stop

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container-fluid">
        <div id="col-main" class="col-xs-12">
            <div class="page-header">
                <h1 id="dashboard">Admin Dashboard</h1>
            </div>
            <div id="admin_tab_panel" role="tabpanel">

                @include('admin.partials.nav-tabs')

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane tab-pane-admin active">
                        <table id="accounts_receivable_table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice Date</th>
                                    <th>Invoice #</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Total Due</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->created_at->format('m-d-Y H:i:s') }}</td>
                                    <td>{{ $order->invoice_num }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->user->email }}</td>
                                    <td>${{ number_format($order->details()->whereNull('paid')->orWhere('paid','!=','1')->sum('subtotal'),2) }}</td>
                                    <td>
                                      <button class="btn btn-info" data-toggle="modal" data-target="#order-info" title="Order Information" onclick="order_information('{{ $order->id }}')">
                                        <span class="fa fa-info"></span>
                                      </button>
                                      <button class="btn btn-warning" title="Edit Status for #{{ $order->id }}" data-toggle="modal" data-target="#order-status" onclick="order_status('{{ $order->id }}')">
                                        <span class="fa fa-edit" aria-hidden="true"></span>
                                      </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    $(function(){
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });
        $('#accounts_receivable_table').DataTable({"order": [[ 0, "desc" ]]});
    });
    function order_status(id){
      $.post('{{ route("order-status") }}',{id:id},function(data){
        //console.log(data);
        $('#order-status .modal-body').html(data);
        $('.orderstatus-datepicker').datepicker();
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
