@extends('layout.app')
@section('pageTitle',trans('Check List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <form action="">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Check No')}}</label>
                            <input type="text" class="form-control" value="{{ request('check_no')}}" name="check_no">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="">{{__('Check Date')}}</label>
                            <input type="date" class="form-control" value="{{ request('check_date')}}" name="check_date">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="mm">{{__('Memu')}}</label>
                            <input type="text" class="form-control" value="{{ request('memu_no')}}" name="memu_no">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="sr">{{__('SR')}}</label>
                            <select name="sr_id" class="select2 form-select">
                                <option value="">Select</option>
                                @forelse ($sr as $d)
                                    <option value="{{$d->id}}" {{ request('sr_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                @empty
                                    <option value="">No Data Found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6 d-flex justify-content-end">
                            <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                        </div>
                        <div class="col-6 d-flex justify-content-Start">
                            <a href="{{route(currentUser().'.checkDetail.index')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                        </div>
                    </div>
                </form>
                <!-- table bordered -->
                <div class="table-responsive">
                    <a class="float-end" href="{{route(currentUser().'.checkDetail.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    <table class="table table-bordered mb-0 table-striped">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('SR')}}</th>
                                <th scope="col">{{__('Shop')}}</th>
                                <th scope="col">{{__('Bank Name')}}</th>
                                <th scope="col">{{__('Check No')}}</th>
                                <th scope="col">{{__('Check Date')}}</th>
                                <th scope="col">{{__('Cash Date')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Memu No')}}</th>
                                <th scope="col">{{__('Amount')}}</th>
                                <th scope="col">{{__('Collection')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key=>$p)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$p->sr?->name}}</td>
                                <td>{{$p->shop?->shop_name}}</td>
                                <td>{{$p->bank_name}}</td>
                                <td>{{$p->check_number}}</td>
                                <td>{{$p->check_date}}</td>
                                <td>{{$p->cash_date}}</td>
                                <td>{{$p->check_status}}</td>
                                <td>{{$p->memu_number}}</td>
                                <td>{{$p->amount}}</td>
                                <td>{{$p->collected_amount}}</td>
                                <td class="white-space-nowrap">
                                    <a class="ms-2" href="{{route(currentUser().'.checkDetail.edit',encryptor('encrypt',$p->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="12" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="my-3">
                    {!! $data->withQueryString()->links()!!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection