@extends('layout.app')
@section('pageTitle',trans('Sales Report List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-12">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-2 py-1">
                                    <label for="fdate">{{__('From Date')}}</label>
                                    <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                                </div>
                                <div class="col-2 py-1">
                                    <label for="fdate">{{__('To Date')}}</label>
                                    <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                                </div>
                                <div class="col-3 py-1">
                                    <label for="sr">{{__('Distributor')}}</label>
                                    <select name="distributor_id" class="form-control form-select">
                                        <option value="">Select</option>
                                        @forelse ($distributor as $d)
                                            <option value="{{$d->id}}" {{ request('distributor_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-3 py-1">
                                    <label for="sr">{{__('Product')}}</label>
                                    <select name="product_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($product as $p)
                                            <option value="{{$p->id}}" {{ request('product_id')==$p->id?"selected":""}}>{{$p->product_name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-2 py-1">
                                    <label for="sr">{{__('SR')}}</label>
                                    <select name="sr_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($userSr as $p)
                                            <option value="{{$p->id}}" {{ request('sr_id')==$p->id?"selected":""}}>{{$p->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-12 col-sm-12 ps-0 text-center py-2">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a class="btn btn-sm btn-warning " href="{{route(currentUser().'.sales_report')}}" title="Clear">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="row">
                    <div class="col-8">
                        <div class="table-responsive">
                            @php
                                $totalAmount = 0;
                                $totalQty = 0;
                                $totalDamageQty = 0;
                                $totalWithDamageQty = 0;
                                $sl=1;
                            @endphp
                            <table class="table table-bordered mb-0 table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">{{__('#SL')}}</th>
                                        <th scope="col">{{__('Product')}}</th>
                                        <th class="text-center" scope="col">{{__('Qty')}}</th>
                                        <th class="text-center" scope="col">{{__('Damage')}}</th>
                                        <th class="text-center" scope="col">{{__('Total')}}</th>
                                        <th class="text-center" scope="col">{{__('Total Tk')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales as $p)
                                    <tr>
                                        <td>{{$sl++}}</td>
                                        <td>{{$p->product?->product_name}}</td>
                                        <td class="text-end">{{money_format($p->total_sales_pcs)}}</td>
                                        <td class="text-end">
                                            @if ($p->total_damage_pcs > 0)
                                                {{money_format($p->total_damage_pcs)}}
                                            @endif
                                        </td>
                                        <td class="text-end">{{money_format($p->total_sales_pcs+$p->total_damage_pcs)}}</td>
                                        <td class="text-end">{{ money_format($p->subtotal_price)}}</td>
                                    </tr>
                                    @php
                                        $totalQty += $p->total_sales_pcs;
                                        $totalDamageQty += $p->total_damage_pcs;
                                        $totalWithDamageQty += ($p->total_sales_pcs+$p->total_damage_pcs);
                                        $totalAmount += $p->subtotal_price;
                                    @endphp
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Data Found</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-end">{{money_format($totalQty)}}</th>
                                        <th class="text-end">{{money_format($totalDamageQty)}}</th>
                                        <th class="text-end">{{money_format($totalWithDamageQty)}}</th>
                                        <th class="text-end">{{money_format($totalAmount)}}</th>
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
        </div>
    </div>
</section>
@endsection
