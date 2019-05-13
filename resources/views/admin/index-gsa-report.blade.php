@extends('layout')


@section('title') Admin Dashboard ::
@parent
@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $('#orders_table').DataTable({
            "order": [
                [1, "desc"]
            ]
        });
        $('#date_range').on('change', function(e) {
            location.href = "{{ route('admin-gsa-report') }}?date_range=" + $(this).val();
        });
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

                @include('admin.partials.nav-tabs', ["adminActive"=>'Orders'])

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane tab-pane-admin active">

                        <div class="form-group form-inline text-right">
                            <label for="date_range">Date Range: </label>
                            <select id="date_range" name="date_range" class="form-control">
                                <option value="This Month" {!! request()->input('date_range')=='This Month'?'selected="selected"':'' !!}>This Month</option>
                                <option value="Last Month" {!! request()->input('date_range')=='Last Month'?'selected="selected"':'' !!}>Last Month</option>
                                <option value="This Quarter" {!! request()->input('date_range')=='This Quarter'?'selected="selected"':'' !!}>This Quarter</option>
                                <option value="Last Quarter" {!! request()->input('date_range')=='Last Quarter'?'selected="selected"':'' !!}>Last Quarter</option>
                                <option value="YTD" {!! request()->input('date_range')=='YTD'?'selected="selected"':'' !!}>YTD</option>
                                <option value="Last Year" {!! request()->input('date_range')=='Last Year'?'selected="selected"':'' !!}>Last Year</option>
                            </select>
                        </div>

                        <table id="orders_table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Order Date</th>
                                    <th>Invoice #</th>
                                    <th>PO #</th>
                                    <th>Solicitation #</th>
                                    <th>Ship Status</th>
                                    <th>Name</th>
                                    <th>Shipping</th>
                                    <th>Phone #</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($orders)
                                    @foreach($orders as $order)
                                        <tr id="{{ $order->id }}">
                                            <td>{{ $order->created_at->format('m-d-Y') }}</td>
                                            <td>{{ $order->invoice_num }}</td>
                                            <td>{{ $order->transaction?$order->transaction->purchase_order_num:'' }}</td>
                                            <td>{{ $order->solicitation_number }}</td>
                                            <td>{{ $order->shipstatus }}</td>
                                            <td>{{ $order->shippingname }}</td>
                                            <td>{{ $order->address1.' '.$order->address2.' '.$order->city.', '.$order->state.' '.$order->zip }}</td>
                                            <td>{{ $order->phone.' '.($order->secondary_phone?'or '.$order->secondary_phone:'') }}</td>
                                            <td>${{ number_format($order->total,2,'.',',') }}</td>
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
@stop
