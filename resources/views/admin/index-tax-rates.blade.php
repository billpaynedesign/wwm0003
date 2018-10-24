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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-tax-rate">
                                <span class="fa fa-dollar-sign" aria-hidden="true"></span>&nbsp; Add Tax
                            </button>
                        </div>
                        <table id="taxrates_table" class="table table-striped table-hover text-left">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Tax</th>
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
    <div class="modal fade" id="add-tax-rate" tabindex="-1" role="dialog" aria-labelledby="add-tax-rate" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('tax-rate-new') }}" method="post" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="add-inventory-title">Add Tax Rate</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name')?old('name'):'' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tax">Tax (%):</label>
                            <input type="text" class="form-control" id="tax" name="tax" placeholder="6.5" value="{{ old('tax')?old('tax'):'' }}" required>
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
    <div class="modal fade" id="edit-tax-rate" tabindex="-1" role="dialog" aria-labelledby="add-tax-rate" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="add-inventory-title">Add Tax Rate</h4>
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
        $('#taxrates_table').DataTable({
            searchDelay: 500,
            serverSide: true,
            ajax: '{{ route('admin-tax-rates') }}',
            stateSave: true,
            stateDuration: 1800,
            "columns": [{
                    "data": "name",
                    "name": "name"
                },
                {
                    "data": "tax",
                    "name": "tax"
                },
                {
                    "data": "action",
                    "name": "action",
                    "orderable": false,
                    "searchable": false,
                },
            ]

        });
        $('#edit-tax-rate').on('show.bs.modal',function(event){
            var button = $(event.relatedTarget);
            var trid = button.data('trid');
            var name = button.data('name');
            var tax = button.data('tax');
            var formaction = `{{ route('tax-rate-update','') }}/${trid}`;
            var modal = $(this);
            modal.find('.modal-title').text(button.title);
            modal.find('form').attr('action',formaction);
            modal.find('.modal-body #edit_name').val(name);
            modal.find('.modal-body #edit_tax').val(tax);
        }).on('hide.bs.modal',function(event){
            var modal = $(this);
            modal.find('.modal-title').text('');
            modal.find('form').attr('action','');
            modal.find('.modal-body #edit_name').val('');
            modal.find('.modal-body #edit_tax').val('');
        });
    });
</script>
@stop
