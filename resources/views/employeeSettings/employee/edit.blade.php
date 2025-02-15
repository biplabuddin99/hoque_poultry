@extends('layout.app')

@section('pageTitle',trans('Update Employee'))
@section('pageSubTitle',trans('Update'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" action="{{route(currentUser().'.employee.update',encryptor('encrypt',$emdata->id))}}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$emdata->id)}}">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="code">{{__('Employee Code')}}<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('employee_code',$emdata->employee_code)}}" name="employee_code">
                                            {{-- @if($errors->has('code'))
                                                <span class="text-danger"> {{ $errors->first('code') }}</span>
                                            @endif --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="designation">{{__('Designation')}}<span class="text-danger">*</span></label>
                                            <select required name="designation" class="form-control form-select" >
                                                <option value="">Select</option>
                                                @forelse($designation as $d)
                                                    <option value="{{$d->id}}" {{ old('designation',$emdata->designation_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{__('Name')}}</label>
                                            <input type="text" class="form-control" value="{{ old('name',$emdata->name)}}" name="name">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{__('Father Name')}}</label>
                                            <input type="text" class="form-control" value="{{ old('father_name',$emdata->father_name)}}" name="father_name">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{__('Mother Name')}}</label>
                                            <input type="text" class="form-control" value="{{ old('mother_name',$emdata->mother_name)}}" name="mother_name">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Contact')}}</label>
                                            <input type="text" class="form-control" value="{{ old('contact',$emdata->contact)}}" name="contact">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="area">{{__('Area')}}</label>
                                            <input type="text" class="form-control" value="{{ old('area',$emdata->area)}}" name="area">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Permanent Address')}}</label>
                                            <input type="text" class="form-control" value="{{ old('permanent_address',$emdata->permanent_address)}}" name="permanent_address">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Status')}}</label>
                                            <select name="status" class="form-control form-select">
                                                <option value="">select</option>
                                                <option value="0" {{ old('status',$emdata->status)=="0"?"selected":""}}>Inactive</option>
                                                <option value="1" {{ old('status',$emdata->status)=="1"?"selected":""}}>Active</option>
                                            </select>
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
