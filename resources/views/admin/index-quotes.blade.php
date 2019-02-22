@extends('layout')


@section('title')  Admin Dashboard ::
@parent
@stop

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
                        <div class="form-group form-inline">
                            <a href="{{ route('quote-create') }}" class="btn btn-primary">
                                <span class="fa fa-file-pdf" aria-hidden="true"></span>&nbsp; Add Quote
                            </a>
                        </div>
                        <div class="form-group form-inline pull-right">
                            <div class="checkbox">
                                <label for="archived_checkbox">
                                    <input type="checkbox" id="archived_checkbox" name="archived" value="1" {{ request()->has('archived')?'checked':'' }}> Include Archived?
                                </label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <table id="quotes_table" class="table table-striped table-hover text-left">
                            <thead>
                                <tr>
                                    <th>RFQ #</th>
                                    <th>Quote #</th>
                                    <th>Creation Date</th>
                                    <th>PDF</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('modals')
    <div class="modal fade" id="edit-quote" tabindex="-1" role="dialog" aria-labelledby="edit-quote" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="add-inventory-title">Edit Quote</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Name:</label>
                            <input type="text" class="form-control" id="edit_name" name="name" placeholder="Name" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tax">Tax (%):</label>
                            <input type="text" class="form-control" id="edit_tax" name="tax" placeholder="6.5" value="" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-default">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        var table = $('#quotes_table').DataTable({
            searchDelay: 500,
            serverSide: true,
            ajax: '{{ route('admin-quotes') }}{{ request()->has('archived')?'?archived=true':'' }}',
            stateSave: true,
            stateDuration: 1800,
            "columns": [
                {
                    "data": "rfq_num",
                    "name": "rfq_num"
                },
                {
                    "data": "id",
                    "name": "id"
                },
                {
                    "data": "created_at",
                    "name": "created_at"
                },
                {
                    "data": "pdf_download",
                    "name": "pdf_download"
                },
                {
                    "data": "email",
                    "name": "email"
                },
                {
                    "data": "status",
                    "name": "status"
                },
                {
                    "data": "action",
                    "name": "action",
                    "orderable": false,
                    "searchable": false,
                },
            ]

        });
        $(document).on('change','.status-select',function(){
            var status = $(this).val();
            var quote_id = $(this).attr('data-quoteid');

            $.post('{{ url("quotes/status/update") }}/'+quote_id, {
                status: status,
                _token: '{{ csrf_token() }}'
            }, function(data) {
                table.ajax.reload();
            });
        });
        $('#archived_checkbox').on('change',function(){
            var route = '{{ route('admin-quotes') }}';
            if($(this).is(':checked')){
                location.href = route+'?archived=true';
            }
            else{
                location.href = route;
            }
       });
    });
</script>
@stop
