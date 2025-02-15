@extends('layout.app')
@section('pageTitle',trans('SR Report List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <div class="row pb-1">
                        <div class="col-10">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-4 py-1">
                                        <label for="fdate">{{__('From Date')}}</label>
                                        <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                                    </div>
                                    <div class="col-4 py-1">
                                        <label for="fdate">{{__('To Date')}}</label>
                                        <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                                    </div>
                                    <div class="col-4 py-1">
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
                                        <a class="btn btn-sm btn-warning " href="{{route(currentUser().'.srreport')}}" title="Clear">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-2">
                        </div>
                    </div>
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('DSR/Shop Name')}}</th>
                                    <th scope="col">{{__('Sales Date')}}</th>
                                    <th scope="col">{{__('Product')}}</th>
                                    <th scope="col">{{__('Total')}}</th>
                                    {{--  <th class="white-space-nowrap">{{__('ACTION')}}</th>  --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $p)
                                <tr>
                                    <td>{{ ++$loop->index }}</td>
                                    <td>
                                        @if (!empty($p->shop_id))
                                        <span class="text-danger">Shop :</span> {{ $p->shop?->shop_name }}
                                        @elseif(!empty($p->dsr_id))
                                        <span class="text-info">DSR :</span> {{ $p->dsr?->name }}
                                        @else
                                        @endif
                                    </td>
                                    <td>{{$p->sales_date}}</td>
                                    <td>
                                        @if($p->sales_details)
                                            <table class="table table-bordered mb-0 table-striped">
                                                <tr>
                                                    <td>Product</td>
                                                    <td>Sales(PCS)</td>
                                                    <td>Damage(PCS)</td>
                                                    <td>PCS Price</td>
                                                    <td>Subtotal</td>
                                                </tr>
                                                @foreach($p->sales_details as $detail)
                                                    <tr>
                                                        <td width="40%">{{ $detail->product?->product_name }}</td>
                                                        <td width="15%">{{ $detail->total_sales_pcs}}</td>
                                                        <td width="15%">{{ $detail->pcs_damage}}</td>
                                                        <td width="15%">@if($detail->tp_price) {{ $detail->tp_price }}@else {{ $detail->tp_free }} @endif</td>
                                                        <td width="15%">{{ $detail->subtotal_price }}</td>
                                                    </tr>
                                                @endforeach
                                                {{--  <tr class="text-center">
                                                    <td class="text-end" colspan="9"><h5 for="totaltk">{{__('Total Taka')}}</h5></td>
                                                    <td colspan="10">{{ $p->daily_total_taka }}</td>
                                                </tr>  --}}
                                            </table>
                                        @endif
                                    </td>
                                    <td style="vertical-align: bottom;">{{$p->daily_total_taka}}
                                        <input type="hidden" value="{{$p->daily_total_taka}}" class="final_total">
                                    </td>
                                    {{--  <td>
                                        <a class="ms-2" href="{{route(currentUser().'.sales.printpage',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>  --}}
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="5" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th>
                                        <span class="sumFinalTotal"></span>
                                        {{--  <input type="text" value="" class="sumFinalTotal_f">  --}}
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
<!-- Bordered table end -->
<script>
    function showConfirmation(salesId) {
        if (confirm("Are you sure you want to delete this sales?")) {
            $('#form' + salesId).submit();
        }
    }
</script>

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
        $('.sumFinalTotal_f').val(parseFloat(subtotal).toFixed(2));
    }
</script>
@endpush
