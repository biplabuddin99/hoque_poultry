@extends('layout.app')

@section('pageTitle','Update Distributor')
@section('pageSubTitle','Update')

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.supplier.update',encryptor('encrypt',$supplier->id))}}">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="code">Distributor Code<span class="text-danger">*</span></label>
                                        <input type="text" value="{{old('supplier_code',$supplier->supplier_code)}}" class="form-control" name="supplier_code" required>
                                    </div>
                                    {{-- @if($errors->has('name'))
                                        <span class="text-danger"> {{ $errors->first('name') }}</span>
                                    @endif --}}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Distributor Name<span class="text-danger">*</span></label>
                                        <input type="text" value="{{old('name',$supplier->name)}}" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="contact">Contact No</label>
                                        <input type="text" value="{{old('contact',$supplier->contact)}}" class="form-control" name="contact">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" value="{{old('email',$supplier->email)}}" class="form-control" name="email">
                                    </div>
                                </div>

                                {{--  <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" value="{{old('country',$supplier->country)}}" class="form-control" name="country">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" value="{{old('city',$supplier->city)}}" class="form-control" name="city">
                                    </div>
                                </div>  --}}
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" name="address" rows="2">{{old('address',$supplier->country)}}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info me-1 mb-1">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
