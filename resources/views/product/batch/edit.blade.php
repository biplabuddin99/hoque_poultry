@extends('layout.app')

@section('pageTitle',trans('Update Batch'))
@section('pageSubTitle',trans('Update'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.batch.update',encryptor('encrypt',$batch->id))}}">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="cat">{{__('Batch Name')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('name',$batch->name)}}" name="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="price">{{__('DP')}}</label>
                                        <input type="number" class="form-control" value="{{ old('dp',$batch->dp)}}" name="dp">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="price">{{__('TP')}}</label>
                                        <input type="number" class="form-control" value="{{ old('tp',$batch->tp)}}" name="tp">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="price">{{__('MRP')}}</label>
                                        <input type="number" class="form-control" value="{{ old('mrp',$batch->mrp)}}" name="mrp">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
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
