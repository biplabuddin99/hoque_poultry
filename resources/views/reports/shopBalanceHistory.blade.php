@extends('layout.app')
@section('pageTitle',trans('Shop Balance History'))
@section('pageSubTitle',trans('Reports'))
@section('content')
@php $settings=App\Models\Settings\Company::first(); @endphp
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
                                    <h6><span style="border-bottom: solid 1px;">{{$shop->shop_name}}</span></h6>
                                </th>
                            </tr>
                        </table>
                        <div class="tbl_scroll">
                            @php
                                $inAmount = 0;
                                $inCollectAmount = 0;
                                $outAmount = 0;
                                $outAmountTotal = 0;
                                $dueAmount = 0;
                            @endphp
                            <table class="tbl_border" style="width:100%">
                                <tbody>
                                    <tr class="tbl_border bg-secondary text-white">
                                        <th colspan="2" class="tbl_border" style="text-align: center; padding: 5px;">PARTICULARS</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Balance Out</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Balance In</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Current Balance</th>
                                    </tr>
                                    <tr class="tbl_border bg-secondary text-white">
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Type</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Date</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Delivery</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Cash Receive</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Total</th>
                                    </tr>
                                    
                                    @forelse($data as $d)
                                    <tr class="tbl_border">
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                           @if($d->status=='0') Out @elseif($d->status=='1') In @endif
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            @if ($d->status == '0')
                                                @if ($d->new_due_date != '')
                                                    {{$d->new_due_date}}
                                                @else
                                                    {{$d->check_date}}
                                                @endif
                                            @else
                                                {{$d->old_due_date}}
                                            @endif
                                        </td>
                                        
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            @if ($d->status == '0' || $d->check_type==1)
                                            {{money_format($d->balance_amount - $d->check_collect_amount)}}
                                                @php
                                                    $outAmount += $d->balance_amount - $d->check_collect_amount;
                                                @endphp
                                            @endif
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            @if ($d->status == '1')
                                                {{money_format($d->balance_amount)}}
                                                @php
                                                    $inAmount += $d->balance_amount;
                                                @endphp
                                            @endif
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    @foreach ($dueCollection as $d)
                                        <tr class="tbl_border">
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            @if($d->status=='0') Out @elseif($d->status=='1') In @endif
                                            </td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">
                                                {{$d->collection_date}}
                                            </td>
                                            
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">
                                                
                                            </td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;">
                                                {{money_format($d->collect_amount)}}
                                                @php
                                                    $inCollectAmount += $d->collect_amount;
                                                @endphp
                                            </td>
                                            <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @php
                                        $dueAmount = ($inAmount+$inCollectAmount)-$outAmount;
                                    @endphp
                                    <tr>
                                        <th class="tbl_border" colspan="2" style="text-align: center; padding: 5px;">Total</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{money_format($outAmount)}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{money_format($inAmount+$inCollectAmount)}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{money_format($dueAmount)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
