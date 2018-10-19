@extends('layout')


@section('title')  Admin Dashboard ::
@parent
@stop

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container-fluid">
        <div id="col-main" class="col-xs-12">
            <div class="page-header">
                <h1 id="dashboard">Purchase Orders</h1>
            </div>
            <div id="admin_tab_panel" role="tabpanel">

                @include('admin.partials.nav-tabs',  ["adminActive"=>'Vendors'])

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane tab-pane-admin active">
                        <table id="purchase_orders_table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>PO #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase_orders  as $purchase_order)
                                <tr>
                                    <td>{{ $purchase_order->invoice_num }}</td>
                                    <td>{{ $purchase_order->date->format('m-d-Y') }}</td>
                                    <td>${{ number_format($purchase_order->total,2) }}</td>
                                    <td>
                                        @if($purchase_order->vendor && $purchase_order->vendor->email)
                                            <a href="mailto:{{ $purchase_order->vendor->email }}&subject=Purchase%20Order%20{{ $purchase_order->invoice_num }}%20from%20World%20Wide%20Medical%20Distributors&body=Dear%20{{ $purchase_order->vendor->name }},%0D%0APurchase%20Order%20{{ $purchase_order->invoice_num }}%20is%20attached.%20Please%20review%20and%20fill%20at%20your%20earliest%20convenience.%0D%0A%0D%0AThank%20You,%0D%0AWorld%20Wide%20Medical%20Distributors" class="btn btn-success">
                                                <span class="fa fa-envelope"></span>
                                            </a>
                                        @endif
                                        <a href="#order-info" data-toggle="modal" data-target="#order-info"  data-poid="{{ $purchase_order->id }}" class="btn btn-info">
                                            <span class="fa fa-info"></span>
                                        </a>
                                        <a href="{{ route('vendor-purchase-order-export',$purchase_order->id) }}" class="btn btn-primary">
                                            <span class="fa fa-file-pdf"></span>
                                        </a>
                                        <a href="{{ route('vendor-purchase-order-edit',$purchase_order->id) }}" class="btn btn-warning">
                                            <span class="fa fa-edit"></span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="form-group"></div>
                        <div class="form-group">
                            <a href="{{ route('admin-vendors') }}" class="btn btn-primary">
                                Back
                            </a>
                        </div>
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
    $('#purchase_orders_table').DataTable({"order": [[ 0, "desc" ]]});
    $('#order-info').on('show.bs.modal',function(event){
        let button = $(event.relatedTarget);
        let poid = button.data('poid')
        let po_url = '{{ route("vendor-purchase-order-export",'') }}/'+poid+'?noprint=1';
        $(this).find('iframe').prop('src',po_url);
    });
});
</script>
@endsection

@section('modals')
<div class="modal fade" id="order-info" tabindex="-1" role="dialog" aria-labelledby="order-info" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="order-info-title">Purchase Order</h4>
            </div>
            <div id="order-info-body" class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src=""></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
