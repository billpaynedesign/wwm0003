@extends('layout')

@section('content')
<div id="row-main" class="row">
    <div id="container-main" class="container">
        <div id="col-main" class="col-xs-12">
            <h1>Edit {{ $vendor->name }}</h1>
            <div class="col-xs-12">
                <form action="{{ route('vendor-update',$vendor->id) }}" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $vendor->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $vendor->email }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $vendor->phone }}">
                    </div>
                    <div class="form-group">
                        <label for="attn">ATTN:</label>
                        <input type="text" class="form-control" id="attn" name="attn" value="{{ $vendor->attn }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $vendor->address }}">
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2:</label>
                        <input type="text" class="form-control" id="address2" name="address2" value="{{ $vendor->address2 }}">
                    </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $vendor->city }}">
                    </div>
                    <div class="form-group">
                        <label for="state">State:</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ $vendor->state }}">
                    </div>
                    <div class="form-group">
                        <label for="zip">Zip:</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ $vendor->zip }}">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" name="cancel" value="true" class="btn btn-cancel">Cancel</button>
                        <button type="submit" name="submit" value="true" class="btn btn-default">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
