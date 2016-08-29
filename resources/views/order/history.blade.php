@extends('app')

@section('title') @parent :: Order History @stop

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#history_table').DataTable({"order": [[ 0, "desc" ]]});
    });
    
    function order_information(id){
      $.post('{{ url("order/modal") }}',{id:id,_token:'{{ csrf_token() }}'},function(data){
        $('#order-info-body').html(data);
      });
    }
  </script>
@endsection

@section('content')
<div class="container main-container no-padding">
  <div class="col-xs-12 main-col">
    <h1>Order History</h1>
    <div class="table-responsive">
      <table id="history_table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Invoice ID</th>
            <th>Payment</th>
            <th>Order Date</th>
            <th>Ship Status</th>
            <th>More Information</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
            <tr>
              <td>{{ $order->invoice_num }}</td>
              <td>{{ $order->transactionStatus?$order->transactionStatus:'Payment Pending' }}</td>
              <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->orderDate)->format('m-d-Y') }}</td>
              <td>{{ $order->shipStatus?$order->shipStatus:'Not Shipped' }}</td>
              <td class="text-center">
                <a href="{{ route('order-show',$order->token) }}" class="btn btn-sm btn-info" title="Order Information"><i class="fa fa-info"></i></a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('partial.sidebar-contact-full')
@endsection



@section('modals')  
<div class="modal fade" id="order-info" tabindex="-1" role="dialog" aria-labelledby="order-info" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="order-info-title">Order Information</h4>
      </div>
      <div id="order-info-body" class="modal-body">
        Loading Order Information...
      </div>
    </div>
  </div>
</div>
@endsection