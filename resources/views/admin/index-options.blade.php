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
        <li role="presentation" class="active"><a href="{{ route('admin-options') }}#dashboard">Product Options</a></li>
        <li role="presentation"><a href="{{ route('admin-orders') }}#dashboard">Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-backorders') }}#dashboard">Back Orders</a></li>
        <li role="presentation"><a href="{{ route('admin-users') }}#dashboard">Users</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane tab-pane-admin active">
          <div class="table-responsive">
            <table class="table table-striped table-hover tablesorter">
              <thead>
                  <tr>
                    <th>Name</th>
                    <th>Options</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($option_groups as $og)
                    <tr>
                        <td>{{ $og->name }}</td>
                        <td>{{ $og->options()->select('option')->get()->implode('option', ', ') }}</td>
                        <td class="text-center">
                          <a href="{{ route('option-group-edit',$og->id) }}" class="btn btn-warning">
                            <span class="glyphicon glyphicon-edit"></span>
                          </a>
                          <form action="{{ route('option.destroy',$og->id) }}" method="POST" role="form" style="display:inline-block;" class="form-inline" onsubmit="return confirm('Are you sure you want to delete this option group?');">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
                          </form>
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

