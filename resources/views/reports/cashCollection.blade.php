@extends('layout.app')
@section('pageTitle',trans('Cash Collection List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-12">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <label for="fdate">{{__('From Date')}}</label>
                                    <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <label for="fdate">{{__('To Date')}}</label>
                                    <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <label for="dsr">{{__('DSR')}}</label>
                                    <select name="dsr_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($userDsr as $p)
                                            <option value="{{$p->id}}" {{ request('dsr_id')==$p->id?"selected":""}}>{{$p->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div> 
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <label for="shop">{{__('Shop')}}</label>
                                    <select name="shop_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($shop as $p)
                                            <option value="{{$p->id}}" {{ request('shop_id')==$p->id?"selected":""}}>{{$p->shop_name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div> 
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <label for="shop">{{__('Distributor')}}</label>
                                    <select name="distributor_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($distributor as $p)
                                            <option value="{{$p->id}}" {{ request('distributor_id')==$p->id?"selected":""}}>{{$p->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div> 
                                <div class="col-12 col-sm-12 ps-0 text-center py-2">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a class="btn btn-sm btn-warning " href="{{route(currentUser().'.cashCollection')}}" title="Clear">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 table-striped">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col"><span class="text-info">DSR</span> / <span class="text-danger">Shop</span></th>
                                <th scope="col">{{__('Distributor')}}</th>
                                <th scope="col">{{__('Sales Date')}}</th>
                                <th scope="col">{{__('Total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $key=>$p)
                            <tr>
                                <td>{{ $sales->firstItem() + $key }}</td>
                                <td>
                                    @if (!empty($p->shop_id))
                                    <span class="text-danger">Shop :</span> {{ $p->shop?->shop_name }}
                                    @elseif(!empty($p->dsr_id))
                                    <span class="text-info">DSR :</span> {{ $p->dsr?->name }}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    {{-- {{ $p->distributor?->name }} --}}
                                    @if (!empty($p->shop_id))
                                     {{ $p->shop?->distributor?->name }}
                                    @elseif(!empty($p->dsr_id))
                                        {{ $p->dsr?->distributor?->name }}
                                    @else
                                    @endif
                                </td>
                                <td>{{$p->sales_date}}</td>
                                <td style="vertical-align: bottom;">{{$p->today_final_cash}}
                                    <input type="hidden" value="{{$p->today_final_cash}}" class="final_total">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="4" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th>
                                    <span class="sumFinalTotal"></span>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="my-3">
                    {!! $sales->withQueryString()->links()!!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push("scripts")
<script>
    total_calculate();

    function total_calculate() {
        var finalTotal = 0;
        $('.final_total').each(function() {
            finalTotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        $('.sumFinalTotal').text(parseFloat(finalTotal).toFixed(2));
    }
</script>
@endpush
