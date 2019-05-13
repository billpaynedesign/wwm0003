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
            <div class="container">
              @if (count($errors) > 0)
                <div class="col-xs-12">
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                </div>
              @endif
              <div class="col-md-6 col-sm-8 col-xs-12">
                <form action="{{ route('specials-update',$special->id) }}" method="POST">
                  <div class="form-group">
                    <label for="header">Header</label>
                    <input type="text" id="header" name="header" class="form-control" value="{{ $special->header }}">
                  </div>
                  <div class="form-group">
                    <label for="subHeader">Sub-Header</label>
                    <input type="text" id="subHeader" name="subHeader" class="form-control" value="{{ $special->secondary }}">
                  </div>
                  <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" id="url" name="url" class="form-control" value="{{ $special->url }}">
                  </div>
                  <div class="form-group">
                    <input type="hidden" name="special_id" value="{{ $special->id }}">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
