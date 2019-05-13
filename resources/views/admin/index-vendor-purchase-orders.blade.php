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
                                    <th>Vendor</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
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
/*
(1).pad(3) // => "001"
(10).pad(3) // => "010"
(100).pad(3) // => "100"
 */
Number.prototype.pad = function(size) {
  var s = String(this);
  while (s.length < (size || 2)) {s = "0" + s;}
  return s;
}
$(function(){
    $('#purchase_orders_table').DataTable({
        searchDelay: 500,
        serverSide: true,
        ajax: '{{ $ajax_url }}',
        stateSave: true,
        stateDuration: 1800,
        order: [[ 0, "desc" ]],
        columns: [{
                "data": "id",
                "name": "id"
            },
            {
                "data": "vendor.name",
                "name": "vendor.name"
            },
            {
                "data": "date",
                "name": "date"
            },
            {
                "data": "total",
                "name": "total"
            },
            {
                "data": "action",
                "name": "action",
                "orderable": false,
                "searchable": false,
            },
        ]

    });
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
