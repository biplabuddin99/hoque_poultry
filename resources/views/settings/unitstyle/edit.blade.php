@extends('layout.app')

@section('pageTitle','Update Unit Style')
@section('pageSubTitle','Update')

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.unitstyle.update',encryptor('encrypt',$unitstyle->id))}}">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" value="{{old('name',$unitstyle?->name)}}" class="form-control" placeholder="Unit Style Name" name="name">
                                    </div>
                                    @if($errors->has('name'))
                                        <span class="text-danger"> {{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="qty">Quantity<span class="text-danger">*</span></label>
                                        <input required type="number" value="{{old('qty',$unit?->qty)}}" class="form-control"  name="qty" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" class="form-control" name="status">
                                            <option value="1" {{old('status',$unitstyle->status)=="1"?"selected":""}} >Active</option>
                                            <option value="0" {{old('status',$unitstyle->status)=="0"?"selected":""}} >Inactive</option>
                                        </select>
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
