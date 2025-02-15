@extends('layout.app')

@section('pageTitle',trans('Demage Individual Product Wise Reports'))
@section('pageSubTitle',trans('Reports'))
@push("styles")
<link rel="stylesheet" href="{{ asset('assets/css/main/full-screen.css') }}">
@endpush
@section('content')
@php $settings=App\Models\Settings\Company::first(); @endphp
<style>
    @media screen and (max-width: 800px) {
  .tbl_scroll {
    overflow: scroll;
  }
}
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="text-end">
                    <button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
                </div>
                <div class="card-content" id="result_show">
                    <style>

                        .tbl_border{
                        border: 1px solid darkblue;
                        border-collapse: collapse;
                        }
                        </style>
                    <div class="card-body">
                        <table style="width: 100%">
                            <tr style="text-align: center;">
                                <th colspan="2">
                                    <h3> {{ $settings->name }}</h3>
                                    <p>Address :{{ $settings->address }}</p>
                                    <p class="mb-1">Contact: {{ $settings->contact }}</p>
                                    <h6><span style="border-bottom: solid 1px;">{{$product->product_name}}</span></h6>
                                    <p>Demage Item</p>
                                </th>
                            </tr>
                        </table>
                        <div class="tbl_scroll">
                            <table class="tbl_border" style="width:100%">
                                <tbody>
                                    {{-- <tr class="tbl_border bg-secondary text-white">
                                        <th colspan="2" class="tbl_border" style="text-align: center; padding: 5px;">PARTICULARS</th>
                                        <th colspan="3" class="tbl_border" style="text-align: center; padding: 5px;">Stock In</th>
                                        <th colspan="3" class="tbl_border" style="text-align: center; padding: 5px;">Stock Out</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Current STOCK</th>
                                    </tr> --}}
                                    <tr class="tbl_border bg-secondary text-white">
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Type</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Date</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity (PCS)</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">DP (PCS)</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Total</th>
                                       {{--  <th class="tbl_border" style="text-align: center; padding: 5px;">Sub Total Dp</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity (PCS)</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">TP (PCS)</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Sub Total Tp</th> 
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Total Quantity</th>--}}
                                    </tr>
                                    @php
                                        $actualQtyTotalIn = 0;
                                        $actualQtyTotalOut = 0;
                                        $actualQtyTotal = 0;
                                        $totalPcsOuts = 0;
                                        $totalAmountOuts = 0;
                                        $totalPcsIns = 0;
                                        $totalAmountIns = 0;
                                    @endphp
                                    @forelse($stock as $s)
                                    <tr class="tbl_border">
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                           @if($s->status_history=='0') Morning Out @elseif($s->status_history=='1') In Return @elseif($s->status_history=='2') In Damage @elseif($s->status_history=='3') Do Receive @elseif($s->status_history=='4') Out Damage @elseif($s->status_history=='5') Out Purchase Return @endif
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            {{ $s->stock_date }}
                                        </td>
                                        @if($s->status_history =='1')
                                            {{-- <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->dp_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->subtotal_dp_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            @php
                                                $actualQtyTotalIn += $s->totalquantity_pcs;
                                                $totalPcsIns += $s->totalquantity_pcs;
                                                $totalAmountIns += $s->subtotal_dp_pcs;
                                            @endphp --}}
                                        @elseif($s->status_history =='2')
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">
                                                Tp- {{$s->tp_price}}
                                            </td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">
                                                {{$s->tp_price * $s->totalquantity_pcs}}
                                            </td>
                                            {{-- <td class="tbl_border" style="text-align: center; padding: 5px;">Sub-Total: - {{$s->totalquantity_pcs*$s->tp_price}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td> --}}
                                            @php
                                                $actualQtyTotalIn += $s->totalquantity_pcs;
                                                $totalPcsIns += $s->totalquantity_pcs;
                                                $totalAmountIns +=(($s->totalquantity_pcs)*($s->tp_price));
                                            @endphp
                                        {{-- @elseif($s->status_history =='3')
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->dp_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->subtotal_dp_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            @php
                                                $actualQtyTotalIn += $s->totalquantity_pcs;
                                                $totalPcsIns += $s->totalquantity_pcs;
                                                $totalAmountIns += $s->subtotal_dp_pcs;
                                            @endphp
                                        @elseif($s->status_history =='0')
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->tp_price}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs*$s->tp_price}}</td>
                                            @php
                                                $actualQtyTotalOut += $s->totalquantity_pcs;
                                                $totalPcsOuts += $s->totalquantity_pcs;
                                                $totalAmountOuts += (($s->totalquantity_pcs)*($s->tp_price));
                                            @endphp
                                        @elseif($s->status_history =='4')
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->tp_price}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs*$s->tp_price}}</td>
                                            @php
                                                $actualQtyTotalOut += $s->totalquantity_pcs;
                                                $totalPcsOuts += $s->totalquantity_pcs;
                                                $totalAmountOuts += (($s->totalquantity_pcs)*($s->tp_price));
                                            @endphp
                                        @elseif($s->status_history =='5') 
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->tp_price}}</td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->totalquantity_pcs*$s->tp_price}}</td>
                                            @php
                                                $actualQtyTotalOut += $s->totalquantity_pcs;
                                                $totalPcsOuts += $s->totalquantity_pcs;
                                                $totalAmountOuts += (($s->totalquantity_pcs)*($s->tp_price));
                                            @endphp--}}
                                        @endif
                                        {{-- <td class="tbl_border" style="text-align: center; padding: 5px;">
                                             @php
                                             $actualQtyTotal= $actualQtyTotalIn- $actualQtyTotalOut;
                                              echo $actualQtyTotal;
                                              @endphp
                                        </td> --}}
                                    </tr>
                                    @empty
                                    <tr>
                                        <th colspan="9">No data Found</th>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="tbl_border" colspan="2" style="text-align: center; padding: 5px;">Total</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalPcsIns}}</th>
                                        <th></th>
                                        <th class="tbl_border"  style="text-align: center; padding: 5px;">{{$totalAmountIns}}</th>
                                        {{--<th class="tbl_border" style="text-align: center; padding: 5px;"></th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalAmountIns}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalPcsOuts}}</th>
                                         <th class="tbl_border" style="text-align: center; padding: 5px;"></th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalAmountOuts}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;"></th> --}}
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{--  <table style="width: 100%; margin-top: 5rem;">
                            <tr style="padding-top: 5rem;">
                                <td style="text-align: center;"><span style="border-bottom: solid 1px;">{{encryptor('decrypt', request()->session()->get('userName'))}}</span></td>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;"></th>
                            </tr>
                            <tr style="padding-top: 5rem;">
                                <th style="text-align: center;"><h6>CHECKED BY</h6></th>
                                <th style="text-align: center;"><h6>VERIFIED BY</h6></th>
                                <th style="text-align: center;"><h6>Authoraised Signatory</h6></th>
                            </tr>
                        </table>  --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')

<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
