@extends('layout.app')

@section('pageTitle',trans('Create Employee leave'))
@section('pageSubTitle',trans('Create'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.emLeave.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="designation">{{__('Employee')}}<span class="text-danger">*</span></label>
                                            <select required name="employee_id" class="form-control form-select" >
                                                <option value="">Select Employee</option>
                                                @forelse($employee as $d)
                                                    <option value="{{$d->id}}" {{ old('employee_id')==$d->id?"selected":""}}> {{ $d->name}} [{{ $d->employee_code}}]</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">{{__('Leave Date Start')}}</label>
                                            <input type="text" class="form-control datepicker" value="{{ old('leave_date_start')}}" name="leave_date_start" placeholder="dd/mm/yyyy">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">{{__('Leave Date End')}}</label>
                                            <input type="text" id="datepicker" class="form-control " value="{{ old('leave_date_end')}}" name="leave_date_end" placeholder="dd/mm/yyyy">
                                            
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="reason">{{__('Leave Reason')}}</label>
                                            <input type="text" class="form-control" value="{{ old('leave_reason')}}" name="leave_reason">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="details">{{__('Application Details')}}</label>
                                            <textarea class="form-control" name="application_details" rows="2">{{ old('application_details')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="img">{{__('Application Image')}}</label>
                                            <input type="file" class="form-control" name="application_image">
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
