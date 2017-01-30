@extends('app')


@section('title') Admin Dashboard :: @parent @stop

@section('content')
<div class="container-fluid main-container no-padding">
  <div class="col-xs-12 main-col">
    <div class="page-header">
      <h1 id="dashboard">Admin Dashboard</h1>
    </div>
    <div id="admin_tab_panel" role="tabpanel">

      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="{{ route('admin-dashboard') }}#dashboard">Home</a></li>
        <li role="presentation"><a href="{{ route('admin-categories') }}#dashboard">Categories</a></li>
        <li role="presentation"><a href="{{ route('admin-products') }}#dashboard">Products</a></li>
        <li role="presentation"><a href="{{ route('admin-options') }}#dashboard">Product Options</a></li>
        <li role="presentation"><a href="{{ route('admin-orders') }}#dashboard">Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-backorders') }}#dashboard">Back Orders</a></li>
        <li role="presentation" class="active"><a href="{{ route('admin-users') }}#dashboard">Users</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane tab-pane-admin active">
          <div class="table-responsive">
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
                      <a href="{{ route('user-info',$user->id) }}" class="btn btn-info" title="Display {{ $user->name }} item/order history information">
                          <span class="fa fa-th-list" aria-hidden="true"></span>
                      </a>

                      <a href="{{ route('user-edit',$user->id) }}" class="btn btn-warning" title="Edit {{ $user->name }} information">
                          <span class="fa fa-edit" aria-hidden="true"></span>
                      </a>

                      <a href="{{ route('user-product', $user->id) }}" class="btn btn-success" title="Add/Edit product pricing for {{ $user->name }}">
                        <span class="fa fa-usd"></span>
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
<hr/>
@stop

