@extends('layout.app')

@section('pageTitle',trans('Shop Due Reports'))
@section('pageSubTitle',trans('Reports'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="text-center"><h4>SHOP DUE STATEMENT  (report)</h4></div>
                        <div class="card-body">
                            <form class="form" method="get" action="">
                                @csrf
                                <div class="row">
                                    {{--  <div class="col-md-2 mt-2">
                                        <label for="fdate" class="float-end"><h6>{{__('From Date')}}</h6></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" id="fdate" class="form-control" value="{{isset($_GET['fdate'])?$_GET['fdate']:''}}" name="fdate">
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label for="tdate" class="float-end"><h6>{{__('To Date')}}</h6></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" id="tdate" class="form-control" value="{{isset($_GET['tdate'])?$_GET['tdate']:''}}" name="tdate">
                                    </div>  --}}

                                    <div class="col-md-4 mt-3">
                                        <label for="supplierName" class=""><h6>{{__('Shop Name/OWner Name')}}</h6></label>
                                        <select class="select2 form-control" name="shop_name" id="shop_name">
                                            <option value="">Select Party</option>
                                            @forelse($shop as $c)
                                                <option value="{{$c->id}}" {{isset($_GET['shop_name'])&& $_GET['shop_name']==$c->id?'selected':''}}> {{ $c->shop_name}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label for="supplierName" class=""><h6>{{__('Distributor')}}</h6></label>
                                        <select class="form-control" name="distributor_id" id="distributor_id">
                                            <option value="">Select distributor</option>
                                            @forelse($distributor as $c)
                                                <option value="{{$c->id}}" {{isset($_GET['distributor_id'])&& $_GET['distributor_id']==$c->id?'selected':''}}> {{ $c->name}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label for="" class=""><h6>{{__('Area')}}</h6></label>
                                        <select class="select2 form-select" name="area">
                                            <option value="">Select area</option>
                                            @foreach ($area as $a)
                                                <option value="{{ $a->id }}" {{isset($_GET['area'])&& $_GET['area']==$a->id?'selected':''}}>{{ $a->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row m-4">
                                    <div class="col-6 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>

                                    </div>
                                    <div class="col-6 d-flex justify-content-Start">
                                        <a href="{{route(currentUser().'.shopdue')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>

                                    </div>
                                </div>
                                <table class="table mb-5">
                                    <thead>
                                        <tr class="bg-primary text-white text-center">
                                            <th class="p-2">{{__('#SL')}}</th>
                                            <th class="p-2" data-title="Party Name">{{__('Distributor')}}</th>
                                            <th class="p-2" data-title="Party Name">{{__('Shop Name')}}</th>
                                            <th class="p-2" data-title="Party Name">{{__('Amount')}}</th>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $d)
                                        <tr class="text-center">
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $d->distributor_name }}</td>
                                            <td>
                                                <a href="{{route(currentUser().'.shop_balance_history',$d->shop_id)}}">{{ $d->shop_name }}</a>
                                                {{-- /
                                                <a href="{{route(currentUser().'.shop_balance_history_two',$d->shop_id)}}">report</a> --}}
                                            </td>
                                            <td>{{ ($d->balance_out)-($d->balance_in+$d->collect_in+$d->collect_in_cash) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <th colspan="4" class="text-center">No data Found</th>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
