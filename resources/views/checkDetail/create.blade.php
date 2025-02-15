@extends('layout.app')
@section('pageTitle',trans('Check Add'))
@section('pageSubTitle',trans('Add'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <form class="form" method="post" action="{{route(currentUser().'.checkDetail.store')}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="lcNo">{{__('SR')}}</label>
                            <select name="sr_id" class="select2 form-select">
                                <option value="">Select</option>
                                @forelse ($sr as $d)
                                    <option value="{{$d->id}}">{{$d->name}}</option>
                                @empty
                                    <option value="">No Data Found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Shop')}}</label>
                            <select name="shop_id" class="select2 form-select">
                                <option value="">Select</option>
                                @forelse ($shop as $d)
                                    <option value="{{$d->id}}">{{$d->shop_name}}</option>
                                @empty
                                    <option value="">No Data Found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Bank Name')}}</label>
                            <input type="text" class="form-control" name="bank_name">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Check No')}}</label>
                            <input type="text" class="form-control" value="" name="check_number" required>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Check Date')}}</label>
                            <input type="date" class="form-control" value="" name="check_date" required>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Amount')}}</label>
                            <input type="number" class="form-control" value="" name="amount" required>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Collection')}}</label>
                            <input type="number" class="form-control" value="" name="collected_amount">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Cash Date')}}</label>
                            <input type="date" class="form-control" value="" name="cash_date">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Check Status')}}</label>
                            <input type="text" class="form-control" value="" name="check_status">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Memu')}}</label>
                            <input type="text" class="form-control" value="" name="memu_number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2 text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection