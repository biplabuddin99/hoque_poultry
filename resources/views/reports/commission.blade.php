@extends('layout.app')

@section('pageTitle',trans('Sales Commission Report'))
@section('pageSubTitle',trans('Reports'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="text-center"><h4>Sales Commission (Report)</h4></div>
                    <div class="card-body">
                        <form class="form" method="get" action="">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12 py-1">
                                    <label for="fdate">{{__('From Date')}}</label>
                                    <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 py-1">
                                    <label for="fdate">{{__('To Date')}}</label>
                                    <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 py-1">
                                    <label for="lcNo">{{__('Distributor')}}</label>
                                    <select name="distributor_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($distributor as $d)
                                            <option value="{{$d->id}}" {{ request('distributor_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 py-1">
                                    <label for="lcNo">{{__('DSR')}}</label>
                                    <select name="dsr_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($userDsr as $d)
                                            <option value="{{$d->id}}" {{ request('dsr_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 py-1">
                                    <label for="lcNo">{{__('SR')}}</label>
                                    <select name="sr_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($userSr as $d)
                                            <option value="{{$d->id}}" {{ request('sr_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <a href="{{route(currentUser().'.sales_commission')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                                </div>
                            </div>
                        </form>
                        <table class="table mb-5">
                            @php
                                $totalComission = 0;
                            @endphp
                            <thead>
                                <tr class="bg-primary text-white text-center">
                                    <th class="p-2">{{__('#SL')}}</th>
                                    <th class="p-2">{{__('Date')}}</th>
                                    <th class="p-2">{{__('DSR')}}</th>
                                    <th class="p-2">{{__('Distributor')}}</th>
                                    <th class="p-2">{{__('SR')}}</th>
                                    <th class="p-2">{{__('Commission')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $s)
                                    @if ($s->commission > 0)
                                        <tr class="text-center">
                                            <th scope="row">{{ ++$loop->index }}</th>
                                            <td>{{$s->sales_date}}</td>
                                            <td>{{$s->dsr?->name}}</td>
                                            <td>{{$s->distributor?->name}}</td>
                                            <td>{{$s->sr?->name}}</td>
                                            <td>{{$s->commission}}</td>
                                        </tr>
                                        @php
                                            $totalComission += $s->commission;
                                        @endphp
                                    @endif
                                @empty
                                <tr>
                                    <th colspan="6" class="text-center">No data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total</th>
                                    <th class="text-center">{{$totalComission}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
