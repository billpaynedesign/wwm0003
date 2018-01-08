@extends('layout')
@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
      <div id="col-main" class="col-xs-12">
        <h2>Browse Top Categories: <a href="#" id="view-all-categories">View all categories</a></h2>
        <div id="top-category-holder">
          <div class="top-category">
            <div class="top-category-header">
              <img src="{{ asset('images/category-icon-diagnostic-equipment.png') }}">
              Diagnostic Equipment
            </div>

            <div class="top-category-content">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. In arcu eros, laoreet non pulvinar non, egestas in lectus. Lorem ipsum dolor sit amet, consectetur.
              <br>
              <a href="#" class="pull-right">Learn More ></a>
              <div class="clearfix"></div>
            </div>
          </div>
          <!--
           //
          -->
          <div class="top-category">
            <div class="top-category-header">
              <img src="{{ asset('images/category-icon-equipment-supplies.png') }}">
              Equipment/Supplies
            </div>

            <div class="top-category-content">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. In arcu eros, laoreet non pulvinar non, egestas in lectus. Lorem ipsum dolor sit amet, consectetur.
              <br>
              <a href="#" class="pull-right">Learn More ></a>
              <div class="clearfix"></div>
            </div>
          </div>
          <!--
           //
          -->
          <div class="top-category">
            <div class="top-category-header">
              <img src="{{ asset('images/category-icon-syringe-needles.png') }}">
              Syringe/Needles
            </div>

            <div class="top-category-content">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. In arcu eros, laoreet non pulvinar non, egestas in lectus. Lorem ipsum dolor sit amet, consectetur.
              <br>
              <a href="#" class="pull-right">Learn More ></a>
              <div class="clearfix"></div>
            </div>
          </div>
          <!--
           //
          -->
          <div class="top-category">
            <div class="top-category-header">
              <img src="{{ asset('images/category-icon-medical-supplies.png') }}">
              Medical Supplies
            </div>

            <div class="top-category-content">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. In arcu eros, laoreet non pulvinar non, egestas in lectus. Lorem ipsum dolor sit amet, consectetur.
              <br>
              <a href="#" class="pull-right">Learn More ></a>
              <div class="clearfix"></div>
            </div>
          </div>
          <!--
           //
          -->
        </div><!-- #top-category-holder -->

        <h2>Top Products: </h2>
        <div id="top-product-holder">
          <div class="top-product col-sm-4 no-padding">
            <div class="col-md-5 top-product-inner">
              <img src="//placehold.it/125x65" src="img-responsive center-block">
            </div>
            <div class="col-md-7 top-product-inner">
              <p>Item Number: INC153050</p>
              <h3>Gloves, EXAM, Latext, Non-Sterile, P/F, Smooth, SM</h3>
            </div>
          </div>
          <!--
           //
          -->
          <div class="top-product col-sm-4 no-padding">
            <div class="col-md-5 top-product-inner">
              <img src="//placehold.it/125x65" src="img-responsive center-block">
            </div>
            <div class="col-md-7 top-product-inner">
              <p>Item Number: INC153050</p>
              <h3>Gloves, EXAM, Latext, Non-Sterile, P/F, Smooth, SM</h3>
            </div>
          </div>
          <!--
           //
          -->
          <div class="top-product col-sm-4 no-padding">
            <div class="col-md-5 top-product-inner">
              <img src="//placehold.it/125x65" src="img-responsive center-block">
            </div>
            <div class="col-md-7 top-product-inner">
              <p>Item Number: INC153050</p>
              <h3>Gloves, EXAM, Latex, Non-Sterile, P/F, Smooth, SM</h3>
            </div>
          </div>
          <!--
           //
          -->
        </div><!-- #top-product-holder -->
    </div>
  </div>
</div>
@endsection