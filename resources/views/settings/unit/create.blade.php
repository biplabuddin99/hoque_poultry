@extends('layout.app')
@section('pageTitle','Create Unit')
@section('pageSubTitle','Create')
@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.unit.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="style">Style Name<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" name="unit_style_id" required>
                                            <option value="">Selet Style</option>
                                            @forelse ($unitstyles as $style)
                                                <option {{old('unit_style_id')==$style->id?"selected":""}} value="{{$style->id}}">{{$style->name}}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Name<span class="text-danger">*</span></label>
                                        <input type="text" value="{{old('name')}}" class="form-control" placeholder="Unit Style Name" name="name" required>
                                    </div>
                                    {{-- @if($errors->has('name'))
                                        <span class="text-danger"> {{ $errors->first('name') }}</span>
                                    @endif --}}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="qty">Quantity<span class="text-danger">*</span></label>
                                        <input type="text" value="{{old('qty')}}" class="form-control"  name="qty" required>
                                    </div>
                                </div>
                                
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