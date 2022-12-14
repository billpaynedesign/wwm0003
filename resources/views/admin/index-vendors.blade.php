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
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-vendor">
                                <span class="fa fa-plus" aria-hidden="true"></span>&nbsp;Add Vendor
                            </button>
                            <a class="btn btn-primary" href="{{ route('vendor-purchase-order-create') }}">
                                <span class="far fa-file-invoice" aria-hidden="true"></span>&nbsp;Create Purchase Order
                            </a>
                            <a class="btn btn-grey" href="{{ route('vendor-purchase-order-index') }}">
                                <span class="fas fa-file-invoice" aria-hidden="true"></span>&nbsp;View Existing Purchase Orders
                            </a>
                        </div>
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
                                    <td>{{ $vendor->full_address }}</td>
                                    <td>{{ $vendor->phone }}</td>
                                    <td>
                                        <a class="btn btn-info" href="{{ route('vendor-show',$vendor->id) }}" title="View existing purchase orders">
                                            <span class="far fa-file-invoice" aria-hidden="true"></span>
                                        </a>
                                        <a class="btn btn-warning" href="{{ route('vendor-edit',$vendor->id) }}" title="Edit Vendor">
                                            <span class="far fa-edit" aria-hidden="true"></span>
                                        </a>
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

@section('modals')
    @include('admin.modals.vendor-add')
@stop
