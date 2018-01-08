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

        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="{{ route('admin-dashboard') }}#dashboard">Home</a></li>
          <li role="presentation"><a href="{{ route('admin-categories') }}#dashboard">Categories</a></li>
          <li role="presentation"><a href="{{ route('admin-products') }}#dashboard">Products</a></li>
          <li role="presentation"><a href="{{ route('admin-options') }}#dashboard">Product Options</a></li>
          <li role="presentation"><a href="{{ route('admin-orders') }}#dashboard">Orders</a></li>
          <li role="presentation"><a href="{{ route('admin-backorders') }}#dashboard">Back Orders</a></li>
          <li role="presentation"><a href="{{ route('admin-users') }}#dashboard">Users</a></li>
        </ul>

        <div class="tab-content">
          <div role="tabpanel" class="tab-pane tab-pane-admin active">
            <div class="row">
              <div class="col-md-6">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Latest Orders</h3>
                  </div>
                  <table class="panel-body table table-bordered">
                    @foreach($latest_orders as $lorder)
                    <tr>
                      <td>
                        {{ $lorder->shippingname }}
                      </td>
                      <td>
                        {{ $lorder->created_at->format('m-d-Y') }}
                      </td>
                      <td>
                        @foreach($lorder->details as $ldetail)
                        @if($ldetail->product)
                        {{ $ldetail->product->category?$ldetail->product->category->name:'Uncategorized ' }} - {{ $ldetail->product->name }}<br/>
                        @endif
                        @endforeach
                      </td>
                      <td>
                        @foreach($lorder->details as $ldetail)
                        {{ $ldetail->quantity }}<br/>
                        @endforeach
                      </td>
                      <td>
                        ${{ \number_format($lorder->total,2) }}
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Latest Products</h3>
                  </div>
                  <table class="panel-body table table-bordered">
                    @foreach($latest_products as $latest)
                    <tr>
                      <td>
                        <img src="{{ asset($latest->picture?'pictures/'.$latest->picture:'images/noimg.gif') }}" class="img-responsive center-block" style="max-height:40px; max-width: 100px;"/>
                      </td>
                      <td>
                       {{ $latest->name }}
                     </td>
                     <td>
                      {{ $latest->category?$latest->category->name:'' }}
                    </td>
                  </tr>
                  @endforeach
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
