@extends('layout.app')

@section('pageTitle',trans('Sales List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section id="result_show">
@php $settings=App\Models\Settings\Company::first(); @endphp
<style>
    @media print {
  @page {
    size:5.845in 8.5in;
    margin: 0.03in;
    
  }
  .print-font{
      font-size:11px;
  }
}
</style>
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body" style="margin-left: 10px; margin-right: 10px;">
                        <div class="row text-center">
                            <h4 class="mb-0"> {{ $settings->name }}</h4>
                            <p class="m-0 print-font">Contact: {{ $settings->contact }}</p>
                            <p class="m-0 print-font">Address :{{ $settings->address }}</p>
                        </div>
                        <table style="width: 100%; margin-top:0;">
                            <tr>
                                <th width="60%">
                                    <table style="width: 100%">
                                        @if (!empty($sales->shop_id))
                                        <tr>
                                            <td style="width: 25%" class="print-font"><b>Shop Name</b></td>
                                            <td style="width: 2%" class="print-font">:</td>
                                            <td style="width: 83%" class="print-font">{{ $sales->shop?->shop_name}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%" class="print-font"><b>Address</b></td>
                                            <td style="width: 2%" class="print-font">:</td>
                                            <td style="width: 83%" class="print-font">{{ $sales->shop?->address }}</td>
                                        </tr>
                                        @endif
                                        @if (!empty($sales->dsr_id))
                                        <tr>
                                            <td style="width: 25%" class="print-font"><b>DSR Name</b></td>
                                            <td style="width: 2%" class="print-font">:</td>
                                            <td style="width: 83%" class="print-font">{{ $sales->dsr?->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%" class="print-font"><b>&nbsp;&nbsp;</b></td>
                                            <td style="width: 2%" class="print-font">&nbsp;</td>
                                            <td style="width: 83%" class="print-font">&nbsp;&nbsp;</td>
                                        </tr>
                                        @endif
                                    </table>
                                </th>
                                <th width="40%">
                                    <table style="width: 100%">
                                        <tr>
                                            <td style="width: 40%; text-align:left;" class="print-font"><b>Sales Date</b></td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 58%; text-align:start;" class="print-font">{{ $sales->sales_date }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 40%; text-align:left;" class="print-font"><b>Area</b></td>
                                            <td style="width: 2%" class="print-font">:</td>
                                            <td style="width: 58%; text-align:start;" class="print-font">{{ $sales->area?->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 40%; text-align:left;" class="print-font"><b>SR</b></td>
                                            <td style="width: 2%" class="print-font">:</td>
                                            <td style="width: 58%; text-align:start;" class="print-font">{{ $sales->sr?->name }}</td>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                        </table>
                        <!-- table bordered -->
                        <div class="mt-0 table-responsive">
                            <table class="table table-bordered mb-0 table-striped print-font">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">{{__('SL')}}</th>
                                        <th scope="col">{{__('Product Name')}}</th>
                                        <th scope="col">{{__('CTN')}}</th>
                                        <th scope="col">{{__('PCS')}}</th>
                                        <th scope="col">{{__('Total')}}</th>
                                        <th scope="col">{{__('PCS Price')}}</th>
                                        <th scope="col">{{__('Sub-Total')}}</th>
                                    </tr>
                                </thead>
                                @php 
                                    $sl = 0;
                                @endphp
                                <tbody id="sales_repeat">
                                    @if ($sales->temporary_sales_details)
                                        @foreach ($sales->temporary_sales_details as $salesdetails)
                                            <tr class="text-center">
                                                <td>{{++$sl}}</td>
                                                <td style="text-align: left;">{{ $salesdetails->product?->product_name }}</td>
                                                <td>{{ $salesdetails->ctn }}</td>
                                                <td>{{ $salesdetails->pcs }}</td>
                                                <td>{{ $salesdetails->totalquantity_pcs }}</td>
                                                <td>{{ $salesdetails->pcs_price }}</td>
                                                <td>
                                                    {{ $salesdetails->subtotal_price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><h6 class="print-font">Total</h6></td>
                                        <td class="text-center">{{ $sales->total }} TK</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><h6 class="print-font">Discount</h6></td>
                                        <td class="text-center">{{ number_format($sales->discount,2) }} TK</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-5">
                            <span style="border-top: solid 2px;" class="print-font">ক্রেতার স্বাক্ষর</span>
                            <span style="border-top: solid 2px;" class="print-font">বিক্রেতার স্বাক্ষর</span>
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
