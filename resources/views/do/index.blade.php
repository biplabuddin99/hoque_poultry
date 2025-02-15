@extends('layout.app')
@section('pageTitle',trans('Do List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <form class="form" method="get" action="">
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
                    </div>
                    <div class="row m-4">
                        <div class="col-6 d-flex justify-content-end">
                            <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>

                        </div>
                        <div class="col-6 d-flex justify-content-Start">
                            <a href="{{route(currentUser().'.docontroll.index')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>

                        </div>
                    </div>
                </form>
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-striped">
                            <a class="float-end" href="{{route(currentUser().'.docontroll.create')}}" style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Supplier')}}</th>
                                    <th scope="col">{{__('Do Date')}}</th>
                                    <th scope="col">{{__('Reference Number')}}</th>
                                    <th scope="col">{{__('Total Qty CTN')}}</th>
                                    <th scope="col">{{__('Total Qty PCS')}}</th>
                                    <th scope="col">{{__('Total Amount')}}</th>
                                    {{--  <th scope="col">{{__('Status')}}</th>  --}}
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key=>$p)
                                <tr>
                                <th scope="row">{{ $data->firstItem() + $key }}</th>
                                    <td>{{$p->supplier?->name}}</td>
                                    <td>{{\Carbon\Carbon::parse($p->do_date)->format('d-m-Y')}}</td>
                                    <td>{{$p->reference_num}}</td>
                                    <td>{{$p->total_qty}}</td>
                                    <td>{{$p->total_pcs_qty}}</td>
                                    <td>{{$p->total_amount}}
                                        <input type="hidden" value="{{$p->total_amount}}" class="final_total">
                                    </td>
                                    {{--  <td>{{$p->name}}</td>  --}}
                                    <td class="white-space-nowrap">
                                        {{--  <a href="{{route(currentUser().'.docontroll.edit',encryptor('encrypt',$p->id))}}">
                                       <i class="bi bi-pencil-square"></i>
                                        </a>  --}}
                                        <a class="ms-2" href="{{route(currentUser().'.docontroll.show',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{route(currentUser().'.doreceive',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-cart-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="8" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Total</th>
                                    <th><span class="sumFinalTotal"></span></th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
