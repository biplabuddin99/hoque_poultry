@extends('layout.app')
@section('pageTitle',trans('বিক্রয় তালিকা'))
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
                                <label for="fdate">{{__('তারিখ হতে')}}</label>
                                <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                <label for="fdate">{{__('তারিখ পর্যন্ত')}}</label>
                                <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                <label for="lcNo">{{__('সেল সেন্টার মালিক')}}</label>
                                <select name="shop_id" class="select2 form-select">
                                    <option value="">Select</option>
                                    @forelse ($shops as $d)
                                        <option value="{{$d->id}}" {{ request('shop_id')==$d->id?"selected":""}}>{{$d->owner_name}}</option>
                                    @empty
                                        <option value="">No Data Found</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                <label for="lcNo">{{__('পন্য')}}</label>
                                <select name="product_id" class="select2 form-select">
                                    <option value="">Select</option>
                                    @forelse ($products as $d)
                                        <option value="{{$d->id}}" {{ request('product_id')==$d->id?"selected":""}}>{{$d->product_name}}</option>
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
                                <a href="{{route(currentUser().'.sales.index')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                            </div>
                        </div>
                    </form>
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <a class="float-end" href="{{route(currentUser().'.sales.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                        <table class="table table-bordered mb-0 table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">তারিখ</th>
                                    <th scope="col">{{__('মালিক')}}</th>
                                    <th scope="col">{{__('ঠিকানা')}}</th>
                                    <th scope="col" class="text-center">{{__('পন্য')}}</th>
                                    <th scope="col">{{__('পিস')}}</th>
                                    <th scope="col">{{__('কেজি')}}</th>
                                    <th scope="col">{{__('গ্রাম')}}</th>
                                    <th scope="col">{{__('দর')}}</th>
                                    <th scope="col">{{__('মোট দর')}}</th>
                                    <th scope="col">{{__('জমা')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                function generateHexColor($name) {
                                    if (!$name) return '#B8B8B8'; // Default color for null values
                                    $hash = md5($name); // Hash the product name
                                    return '#' . substr($hash, 0, 6); // Extract first 6 hex characters
                                }
                            @endphp
                                @forelse($sales as $key=>$p)
                                <tr>
                                    <th scope="row">{{ $sales->firstItem() + $key }}</th>
                                    {{-- <td>{{$p->sales_date}}</td> --}}
                                    <td>{{ \Carbon\Carbon::parse($p->sales_date)->format('d/m/Y') }} </td>
                                    {{-- <td>{{ $p->sales_date->format('j/n/Y') }}</td> --}}
                                    <td>{{ ($p->shop?->owner_name) }}</td>
                                    <td>{{ $p->shop?->address }}</td>
                                    <td class="text-center" class="text-center" style="color: {{ generateHexColor($p->product?->product_name) }}; border-radius: 15px; overflow: hidden;">{{$p->product?->product_name}}</td>
                                    <td>{{$p->product_pcs}}</td>
                                    <td>{{$p->kg}}</td>
                                    <td>{{$p->gm}}</td>
                                    <td>{{$p->product_price}}
                                        <input type="hidden" value="{{$p->total}}" class="final_total">
                                    </td>
                                    <td class="text-primary">{{$p->total}}</td>
                                    <td class="text-danger">{{$p->collect_tk}}
                                        <input type="hidden" value="{{$p->collect_tk}}" class="collect_tk">
                                    </td>
                                    <td class="white-space-nowrap">
                                        {{-- <a class="ms-2" href="{{route(currentUser().'.sales.receiveScreen',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-receipt-cutoff"></i>
                                        </a> --}}
                                        {{-- <a class="ms-2" href="{{route(currentUser().'.sales.show',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a> --}}
                                        {{-- <a class="ms-2" href="{{route(currentUser().'.delivery_invoice',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-file-earmark-break"></i>
                                        </a> --}}
                                        @if($p->status==0)
                                            <a class="ms-2" href="javascript:void()" onclick="showConfirmation({{$p->id}})">
                                                <i class="bi bi-trash" style='color:red'></i>
                                            </a>
                                            <a class="ms-2" href="{{route(currentUser().'.sales.sales_update',encryptor('encrypt',$p->id))}}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                        <form id="form{{$p->id}}" action="{{route(currentUser().'.sales.destroy', encryptor('encrypt', $p->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="11" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                                {{-- <tr class="bg-primary text-white" style="background-color: #328ef0 !important; color: white !important;">
                                    <th colspan="9" class="text-end">মোট টাকা </th>
                                    <th class="text-start">
                                        <span class="sumFinalTotal"></span>
                                         <input type="hidden" value="" class="sumFinalTotal_f">
                                    </th>
                                    <th colspan="2" class="text-start">
                                        <span class="sumfinalCollection"></span>
                                    </th>
                                </tr> --}}
                            </tbody>
                            <tfoot>
                                <tr class="bg-primary text-white">
                                    <th colspan="9" class="text-end">মোট টাকা</th>
                                    <th class="text-start">
                                        <span class="sumFinalTotal"></span>
                                        <input type="hidden" value="" class="sumFinalTotal_f">
                                    </th>
                                    <th colspan="2" class="text-start">
                                        <span class="sumfinalCollection"></span>
                                    </th>
                                </tr>
                            </tfoot>

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
<script>
    total_calculate();
    total_callection();

    function total_calculate() {
        var finalTotal = 0;
        $('.final_total').each(function() {
            finalTotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        // $('.sumFinalTotal').text(parseFloat(finalTotal).toFixed(2));
        $('.sumFinalTotal_f').val(parseFloat(finalTotal).toFixed(2));
    }
    function total_callection() {
        var finalCollection = 0;
        $('.collect_tk').each(function() {
            finalCollection+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        var finalTota=$('.sumFinalTotal_f').val();
        var margetotal= parseFloat(finalTota).toFixed(2) - finalCollection;
        $('.sumFinalTotal').text(parseFloat(margetotal).toFixed(2));
        console.log(margetotal);
        $('.sumfinalCollection').text(parseFloat(finalCollection).toFixed(2));
        // $('.sumfinalCollection_f').val(parseFloat(subtotal).toFixed(2));
    }
</script>
@endsection
