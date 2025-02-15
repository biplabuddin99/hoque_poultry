@extends('layout.app')

@section('pageTitle',trans('Create Werehouse'))
@section('pageSubTitle',trans('Create'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.werehouse.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="werehouse">{{__('Werehouse Name')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('werehouse')}}" name="werehouse" required>
                                        {{-- @if($errors->has('code'))
                                            <span class="text-danger"> {{ $errors->first('code') }}</span>
                                        @endif --}}
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="contact">{{__('Contact Number')}}</label>
                                        <input type="text" class="form-control" value="{{ old('contact')}}" name="contact">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{__('Email')}}</label>
                                        <input type="email" class="form-control" value="{{ old('email')}}" name="email">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="location">{{__('Location')}}</label>
                                        <input type="text" class="form-control" value="{{ old('location')}}" name="location">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="address">{{__('Address')}}</label>
                                        <input type="text" class="form-control" value="{{ old('address')}}" name="address">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
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
