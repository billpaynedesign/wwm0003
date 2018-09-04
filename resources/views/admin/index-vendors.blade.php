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

                @include('admin.partials.nav-tabs',  ["adminActive"=>'Vendors'])

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane tab-pane-admin active">
                        <div class="form-group form-inline">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-vendor">
                                <span class="fa fa-plus" aria-hidden="true"></span>&nbsp;Add Vendor
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover tablesorter">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors  as $vendor)
                                    <tr>
                                        <td>{{ $vendor->name }}</td>
                                        <td>{{ $vendor->email }}</td>
                                        <td>{{ $vendor->address }}</td>
                                        <td>{{ $vendor->phone }}</td>
                                        <td>

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
</div>
@stop

@section('modals')
    @include('admin.modals.vendor-add')
@stop
