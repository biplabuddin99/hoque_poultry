@extends('layout.app')

@section('pageTitle',trans('Create Designation'))
@section('pageSubTitle',trans('Create'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.designation.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-6 offset-3">
                                    <div class="form-group">
                                        <label for="code">{{__('Designation')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('designation')}}" name="designation" required>
                                        {{-- @if($errors->has('code'))
                                            <span class="text-danger"> {{ $errors->first('code') }}</span>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6 offset-3 d-flex justify-content-end">
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
