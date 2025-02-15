@extends('layout.app')
@section('pageTitle',trans('Undeliverd List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-10">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-4">
                                    {{-- <input type="text" name="reference_num" value="{{isset($_GET['reference_num'])?$_GET['reference_num']:''}}" placeholder="Reference Number" class="form-control"> --}}
                                    <select class="form-select" name="supplier_id" required>
                                        <option value="">Select Distributor</option>
                                        @forelse (App\Models\Settings\Supplier::where(company())->get() as $sup)
                                            <option value="{{ $sup->id }}" {{ (request('supplier_id') == $sup->id ? 'selected' : '') }}>{{ $sup->name }}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-2 col-sm-4 ps-0 text-start">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a class="btn btn-sm btn-warning " href="{{route(currentUser().'.undeliverd')}}" title="Clear">Clear</a>
                                </div>
                                <div class="col-2 p-0 m-0">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                    </div>
                </div>
                <!-- table bordered -->
                @php
                    $totalProductQty = 0;
                    $totalFreeQty = 0;
                    $totalDpPcs = 0;
                    $totalAmount = 0;
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 table-striped">
                        {{--  <a class="float-end" href="{{route(currentUser().'.docontroll.create')}}" style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>  --}}
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Referenc Numeber')}}</th> 
                                <th scope="col">{{__('Product Name')}}</th>
                                <th scope="col">{{__('Product Qty(PCS)')}}</th>
                                <th scope="col">{{__('Product Qty(Free)')}}</th>
                                <th scope="col">{{__('DP')}}</th>
                                <th scope="col">{{__('SubTotal')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dodetails as $p)
                            @if($p->qty_pcs > $p->receive_qty || $p->free > $p->receive_free_qty)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$p->doReference?->reference_num}}</td>
                                <td>{{$p->product?->product_name}}</td>
                                <td>{{$p->qty_pcs-$p->receive_qty}}</td>
                                <td>{{$p->free-$p->receive_free_qty}}</td>
                                <td>{{$p->dp_pcs}}</td>
                                <td>{{(($p->qty_pcs-$p->receive_qty)*($p->dp_pcs))+(($p->free-$p->receive_free_qty)*($p->dp_pcs))}}</td>
                            </tr>
                            @php
                                $totalProductQty += $p->qty_pcs-$p->receive_qty;
                                $totalFreeQty += $p->free-$p->receive_free_qty;
                                $totalDpPcs += $p->dp_pcs;
                                $totalAmount += (($p->qty_pcs-$p->receive_qty)*($p->dp_pcs))+(($p->free-$p->receive_free_qty)*($p->dp_pcs));
                            @endphp
                            @endif
                            @empty
                                <tr>
                                    <th colspan="7" class="text-center">No Data Found</th>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-secondary text-white">
                                <th colspan="3" class="text-center">Total</th>
                                <td>{{$totalProductQty}}</td>
                                <td>{{$totalFreeQty}}</td>
                                <td>{{$totalDpPcs}}</td>
                                <td>{{$totalAmount}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="my-3">
                    {{--  {!! $data->links()!!}  --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
