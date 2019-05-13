@extends('layout')


@section('title') Admin Dashboard ::
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
                        <table class="table table-striped table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Admin?</th>
                                    <th>Date Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->admin?'yes':'' }}</td>
                                    <td>{{ $user->created_at->format('m-d-Y') }}</td>
                                    <td>
                                        <a href="{{ route('user-barcodes',$user->id) }}" class="btn btn-primary" title="Print barcodes for frequently ordered items.">
                                            <span class="fa fa-barcode" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('user-info',$user->id) }}" class="btn btn-info" title="Display {{ $user->name }} item/order history information">
                                            <span class="fa fa-th-list" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('user-edit',$user->id) }}" class="btn btn-warning" title="Edit {{ $user->name }} information">
                                            <span class="fa fa-edit" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('user-product', $user->id) }}" class="btn btn-success" title="Add/Edit product pricing for {{ $user->name }}">
                                            <span class="fa fa-dollar-sign"></span>
                                        </a>
                                        <a href="{{ route('user-delete',$user->id) }}" class="btn btn-danger" title="Remove {{ $user->name }}" onclick="return confirm('Are you sure you want to remove the user: {{ $user->name }}');">
                                            <span class="fa fa-trash" aria-hidden="true"></span>
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
