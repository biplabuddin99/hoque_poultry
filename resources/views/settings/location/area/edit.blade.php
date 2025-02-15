@extends('layout.app')

@section('pageTitle',trans('Update Area'))
@section('pageSubTitle',trans('Update'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.area.update',encryptor('encrypt',$area->id))}}">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="upazila_id">Distributor<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" name="distributor_id" required>
                                            <option value="">Select Distributor</option>
                                            @forelse($distributor as $d)
                                                <option value="{{$d->id}}" {{ old('distributor_id',$area->distributor_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="thanaName">Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('name',$area->name)}}" name="name" required>
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
