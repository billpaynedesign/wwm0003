@extends('layout')


@section('title') Admin Dashboard :: @parent @stop

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
</div>
@stop
