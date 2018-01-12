@extends('layout')


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
  $('#backorders_table').DataTable({"order": [[ 0, "desc" ]]});
});
</script>
@stop

@section('content')
<div id="row-main" class="row">
  <div id="container-main" class="container-fluid">
    <div id="col-main" class="col-xs-12">
      <div class="page-header">
        <h1 id="dashboard">Admin Dashboard</h1>
      </div>
      <div id="admin_tab_panel" role="tabpanel">

        @include('admin.partials.nav-tabs', ["adminActive"=>'Back Orders'])

        <div class="tab-content">
          <div role="tabpanel" class="tab-pane tab-pane-admin active">
            <div class="form-group">
              <a href="{{ route('order-print-backordered') }}" target="_blank" class="btn btn-info" >Print Backorder&nbsp;&nbsp;<span class="glyphicon glyphicon-print"></span></a>
            </div>
            <div class="table-responsive text-left">
              <table id="backorders_table" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Invoice ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Item</th>
                    <th>Manufacturer</th>
                    <th>Item Number</th>
                    <th>Lot Number</th>
                    <th>Expiry Date</th>
                    <th>Price</th>
                    <th>Backordered</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($backorders as $order)
                    @foreach($order->details()->where('backordered','>',0)->get() as $detail)
                      <tr>
                        <td>{{ $order->invoice_num }}</td>
                        <td>{{ $order->shippingname }}</td>
                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->orderDate)->format('m-d-Y') }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->product->manufacturer }}</td>
                        <td>{{ $detail->product->item_number }}</td>
                        <td>{{ $detail->lot_number }}</td>
                        <td>{{ $detail->expirations }}</td>
                        <td>
                          @if($order->user->product_price_check($detail->product->id))
                            {{ $order->user->product_price_check($detail->product->id)->price_string }}
                          @else
                            {{ $detail->product->min_price_string }}
                          @endif
                        </td>
                        <td>{{ $detail->backordered }}</td>
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
  </div>
</div>
@stop

