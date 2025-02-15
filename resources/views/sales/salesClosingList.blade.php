@extends('layout.app')
@section('pageTitle',trans('Sales Closing List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <form action="">
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
                                <label for="lcNo">{{__('Distributor')}}</label>
                                <select name="distributor_id" class="select2 form-select">
                                    <option value="">Select</option>
                                    @forelse ($distributors as $d)
                                        <option value="{{$d->id}}" {{ request('distributor_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                    @empty
                                        <option value="">No Data Found</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                <label for="lcNo">{{__('SR')}}</label>
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
                        <div class="row m-4">
                            <div class="col-6 d-flex justify-content-end">
                                <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                            </div>
                            <div class="col-6 d-flex justify-content-Start">
                                <a href="{{route(currentUser().'.salesClosingList')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                            </div>
                        </div>
                    </form>
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col"><span class="text-info">DSR</span> / <span class="text-danger">Shop</span></th>
                                    <th scope="col">{{__('SR')}}</th>
                                    <th scope="col">{{__('Distributor')}}</th>
                                    <th scope="col">{{__('Sales Date')}}</th>
                                    <th scope="col">{{__('Total')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $key=>$p)
                                <tr>
                                    <th scope="row">{{ $sales->firstItem() + $key }}</th>
                                    <td>
                                        @if (!empty($p->shop_id))
                                        <span class="text-danger">Shop :</span> {{ $p->shop?->shop_name }}
                                        @elseif(!empty($p->dsr_id))
                                        <span class="text-info">DSR :</span> {{ $p->dsr?->name }}
                                        @else
                                        @endif
                                        {{--  @if (!empty($p->shop->shop_name))
                                        <span class="text-warning">Shop :</span> {{ $p->shop?->shop_name }}
                                        @else
                                        <span class="text-warning">DSR :</span> {{ $p->dsr?->name }}
                                        @endif  --}}
                                    </td>
                                    <td>{{$p->sr?->name}}</td>
                                    <td>{{$p->distributor?->name}}</td>
                                    <td>{{$p->sales_date}}</td>
                                    <td>{{$p->daily_total_taka}}
                                        <input type="hidden" value="{{$p->daily_total_taka}}" class="final_total">
                                        {{-- <input type="hidden" value="{{$p->final_total}}" class="final_total"> --}}
                                    </td>
                                    <td class="white-space-nowrap">
                                        {{--  <a class="ms-2" href="{{route(currentUser().'.sales.receiveScreen',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-receipt-cutoff"></i>
                                        </a>  --}}
                                        <a class="ms-2" href="{{route(currentUser().'.sales.printpage',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        {{--  @if($p->status==0)
                                            <a class="ms-2" href="javascript:void()" onclick="showConfirmation({{$p->id}})">
                                                <i class="bi bi-trash" style='color:red'></i>
                                            </a>
                                            <a class="ms-2" href="{{route(currentUser().'.sales.primary_update',encryptor('encrypt',$p->id))}}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                        <form id="form{{$p->id}}" action="{{route(currentUser().'.sales.destroy', encryptor('encrypt', $p->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form>  --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="7" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                                <tr>
                                    <th colspan="5" class="text-center">Total</th>
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
