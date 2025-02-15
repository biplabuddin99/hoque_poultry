@extends('layout.app')

@section('pageTitle',trans('Delivery Invoice'))
@section('pageSubTitle',trans('invoice'))

@section('content')
<section id="result_show">
    <style>

        .tbl_border{
        border: 1px solid rgb(46, 46, 46);
        border-collapse: collapse;
        }
    </style>
@php $settings=App\Models\Settings\Company::first(); @endphp
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row text-center">
                            <h5> <span class="bg-secondary text-white p-1">Delivery Invoice</span></h5>
                            <h3 class="mb-1">{{ $sales->distributor?->name }}</h3>
                            <p>Address :{{ $settings->address }}</p>
                        </div>
                        <div class="row mt-4">
                            <!-- table bordered -->
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        @if (!empty($sales->shop_id))
                                        <td style="width: 15%"><b>Shop Name</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 51%">{{ $sales->shop?->shop_name}}</td>
                                        @endif
                                        @if (!empty($sales->dsr_id))
                                        <td style="width: 15%"><b>DSR Name</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 51%">{{ $sales->dsr?->name }}</td>
                                        @endif
                                        <td style="width: 15%"><b>Sales Date</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 15%">{{ !is_null($sales->sales_date) ? date('d/m/Y', strtotime($sales->sales_date)) : '' }}</td>
                                    </tr>
                                </table>
                                <table class="table tbl_border mb-0">
                                    <thead>
                                        <tr class="text-center tbl_border bg-secondary text-white">
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col" rowspan="2" width=10%>{{__('ক্রমিক নং')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col" rowspan="2" width=55%>{{__('পণ্যের নাম')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col" colspan="2" width=12%>{{__('গ্রহণ')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col" colspan="3" width=23%>{{__('ফেরত')}}</th>
                                        </tr>
                                        <tr class="text-center bg-secondary text-white">
                                            <th class="tbl_border" style="text-align: center; padding: 5px;">{{__('কাটুন')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;">{{__('পিছ')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;">{{__('কাটুন')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;">{{__('পিছ')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;">{{__('ড্যামেজ')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sales_repeat">
                                        @if ($salesDetails)
                                            @foreach ($salesDetails as $d)
                                                <tr class="text-center">
                                                    <td class="tbl_border">{{ ++$loop->index }}</td>
                                                    <td class="tbl_border">{{ $d->product?->product_name }}</td>
                                                    <td class="tbl_border">{{ $d->ctn }}</td>
                                                    <td class="tbl_border">{{ $d->totalquantity_pcs }}</td>
                                                    <td class="tbl_border"></td>
                                                    <td class="tbl_border"></td>
                                                    <td class="tbl_border"></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr class="tbl_border">
                                            <th class="tbl_border" colspan="7">নগদ গ্রহণ @if($sales->receive_amount != '') = {{$sales->receive_amount}} টাকা @endif</th>
                                        </tr>
                                        <tr class="tbl_border bg-secondary text-white">
                                            <th></th>
                                            <th class="tbl_border text-center" colspan="4">দোকানের নাম</th>
                                            <th class="tbl_border text-center" colspan="2">টাকা</th>
                                        </tr>
                                        @forelse ($shopBalance as $b)
                                            <tr class="tbl_border">
                                                <th class="tbl_border text-center" style="text-align: center; padding: 2px;">{{ ++$loop->index }}</th>
                                                <td class="tbl_border" colspan="4" style="text-align: center; padding: 2px;">{{$b->due_shop?->shop_name}}</td>
                                                <td class="tbl_border" colspan="2" style="text-align: center; padding: 2px;">{{$b->due_amount}}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between mt-5">
                                <span style="border-top: solid 2px;">DSR Signature</span>
                                <span style="border-top: solid 2px;">Authorize Signature</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
@endsection
{{--  @push('scripts')
<script>
    function printDiv(divName) {
        var prtContent = document.getElementById(divName);
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write('<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" type="text/css"/>');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.onload =function(){
            WinPrint.focus();
            WinPrint.print();
        }

       // WinPrint.close();

    }
</script>
@endpush  --}}
