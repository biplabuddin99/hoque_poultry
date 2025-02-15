@extends('layout.app')

@section('pageTitle',trans('Print Sales Closing'))
@section('pageSubTitle',trans('Show'))

@section('content')
@php $settings=App\Models\Settings\Company::first(); @endphp
<section id="result_show">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row text-center">
                            <h3> {{ $settings->name }}</h3>
                            <p>Address :{{ $settings->address }}</p>
                            <p class="mb-1">Contact: {{ $settings->contact }}</p>
                        </div>
                        <div class="ps-2">
                            <table style="width: 100%;">
                                @if (!empty($sales->shop_id))
                                <tr>
                                    <td style="width: 15%"><b>Shop Name</b></td>
                                    <td style="width: 2%">:</td>
                                    <td style="width: 83%">{{ $sales->shop?->shop_name}}</td>
                                </tr>
                                @endif
                                @if (!empty($sales->dsr_id))
                                <tr>
                                    <td style="width: 15%"><b>DSR Name</b></td>
                                    <td style="width: 2%">:</td>
                                    <td style="width: 83%">{{ $sales->dsr?->name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="width: 15%"><b>SR</b></td>
                                    <td style="width: 2%">:</td>
                                    <td style="width: 83%">{{ $sales->sr?->name }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 15%"><b>Sales Date</b></td>
                                    <td style="width: 2%">:</td>
                                    <td style="width: 83%">{{ $sales->sales_date }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 15%"><b>Area</b></td>
                                    <td style="width: 2%">:</td>
                                    <td style="width: 83%">{{ $sales->area?->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- table bordered -->
                        <div class="row ps-2 mt-2 mb-2">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th rowspan="2">{{__('SL')}}</th>
                                            <th rowspan="2">{{__('Product Name')}}</th>
                                            <th rowspan="2">{{__('CTN')}}</th>
                                            <th rowspan="2">{{__('PCS')}}</th>
                                            <th colspan="2">{{ __('Return') }}</th>
                                            <th colspan="2" class="text-danger">{{ __('Damage') }}</th>
                                            <th rowspan="2">{{__('Sales(PCS)')}}</th>
                                            <th rowspan="2">{{__('PCS(Price)')}}</th>
                                            <th rowspan="2">{{__('Sub-Total(Price)')}}</th>
                                        </tr>
                                        <tr>
                                            <th>CTN</th>
                                            <th>PCS</th>
                                            <th class="text-danger">CTN</th>
                                            <th class="text-danger">PCS</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $sl = 0;
                                    @endphp
                                    <tbody id="sales_repeat">

                                        @if ($sales->sales_details)
                                            @foreach ($sales->sales_details as $salesdetails)
                                            @if ($salesdetails->status==0)
                                            <tr class="text-center">
                                                <td>{{++$sl}}</td>
                                                <td>{{ $salesdetails->product?->product_name }}</td>
                                                <td>{{ $salesdetails->ctn }}</td>
                                                <td>{{ $salesdetails->pcs }}</td>
                                                <td>{{ $salesdetails->ctn_return }}</td>
                                                <td>{{ $salesdetails->pcs_return }}</td>
                                                <td>{{ $salesdetails->ctn_damage }}</td>
                                                <td>{{ $salesdetails->pcs_damage }}</td>
                                                <td>{{ $salesdetails->total_sales_pcs }}</td>
                                                <td>
                                                    @if($salesdetails->tp_price) {{ $salesdetails->tp_price }}@else {{ $salesdetails->tp_free }} @endif
                                                </td>
                                                <td>{{ $salesdetails->subtotal_price }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endif
                                        <tr class="text-center">
                                            <td class="text-end" colspan="10"><h5 for="totaltk">{{__('Total Taka')}}</h5></td>
                                            <td colspan="10">{{ $sales->daily_total_taka }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot id="tfootSection">
                                        @if ($sales->sales_details->contains('status', 1))
                                            <tr class="text-center">
                                                <th rowspan="2" colspan="3">{{__('Product Name')}}</th>
                                                {{--  <th rowspan="2">{{__('CTN')}}</th>  --}}
                                                {{--  <th rowspan="2">{{__('PCS')}}</th>  --}}
                                                <th colspan="2">{{ __('Return') }}</th>
                                                <th colspan="2" class="text-danger">{{ __('Damage') }}</th>
                                                <th rowspan="2" colspan="2">{{__('PCS(Price)')}}</th>
                                                <th rowspan="2">{{__('Sub-Total(Price)')}}</th>
                                            </tr>
                                            <tr>
                                                <th>CTN</th>
                                                <th>PCS</th>
                                                <th class="text-danger">CTN</th>
                                                <th class="text-danger">PCS</th>
                                            </tr>
                                            @if ($sales->sales_details)
                                            @foreach ($sales->sales_details as $salesdetails)
                                            @if ($salesdetails->status==1)
                                            <tr  class="text-center">
                                                <td colspan="3">{{ $salesdetails->product?->product_name }}</td>
                                                <td>{{ $salesdetails->ctn_return }}</td>
                                                <td>{{ $salesdetails->pcs_return }}</td>
                                                <td>{{ $salesdetails->ctn_damage }}</td>
                                                <td>{{ $salesdetails->pcs_damage }}</td>
                                                <td colspan="2">{{ $salesdetails->tp_price }}</td>
                                                {{--  <td>{{ $salesdetails->tp_price }}</td>  --}}
                                                <td>{{ $salesdetails->subtotal_price }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            @endif
                                            @if ($salesdetails->status==1)
                                            <tr>
                                                <td class="text-end" colspan="9"><h6 for="return_total">{{__('Return Total Taka')}}</h6></td>
                                                <td  class="text-center" colspan="10">{{ $sales->return_total_taka }} </td>
                                            </tr>
                                            @endif
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-4"></div>
                        <div class="col-8">
                            <table class="table table-bordered mb-0 table-striped">
                                @if($sales->shop_balance->contains('status', 1))
                                    <tr>
                                        <td class="text-center" colspan="4"><h6>Old Due</h6></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2"><b>Shop</b></td>
                                        <td class="text-center"><b>Memu</b></td>
                                        <td class="text-center"><b>Price</b></td>
                                    </tr>
                                    @foreach ($sales->shop_balance as $balance)
                                        @if($balance->status==1 && $balance->balance_amount > 0)
                                        <tr class="text-center">
                                            <td colspan="2">{{ $balance->shop?->shop_name }}</td>
                                            <td>{{ $balance->reference_number }}</td>
                                            <td>{{ $balance->balance_amount }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach ($sales->shop_balance as $balance)
                                        @if($balance->status==0 && $balance->cash_type == 3 && $balance->collect_amount > 0)
                                        <tr class="text-center">
                                            <td colspan="2">{{ $balance->shop?->shop_name }}</td>
                                            <td>{{ $balance->reference_number }}</td>
                                            <td>{{ $balance->collect_amount }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif

                                @if($sales->shop_balance->contains('status', 0))
                                    <tr>
                                        <td class="text-center" colspan="4"><h6>New Due</h6></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="2"><b>Shop</b></td>
                                        <td><b>Memu</b></td>
                                        <td><b>Price</b></td>
                                    </tr>
                                    @foreach ($sales->shop_balance as $balance)
                                        @if($balance->status==0 && $balance->check_type != 1 && $balance->balance_amount > 0)
                                        <tr class="text-center">
                                            <td colspan="2">{{ $balance->shop?->shop_name }}</td>
                                            <td>{{ $balance->reference_number}}</td>
                                            <td>{{ $balance->balance_amount -  $balance->collect_amount}}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @php
                                    $hasBalance = $sales->shop_balance->contains(function ($balance) {
                                        return $balance->status == 0 && $balance->check_type == 1;
                                    });
                                @endphp

                                @if($hasBalance)
                                    <tr>
                                        <td class="text-center" colspan="4"><h6>Check</h6></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="2"><b>Shop</b></td>
                                        <td><b>Memu</b></td>
                                        <td><b>Price</b></td>
                                    </tr> 
                                    @foreach ($sales->shop_balance as $balance)
                                        @if($balance->status == 0 && $balance->check_type == 1 && $balance->balance_amount > 0)
                                        <tr class="text-center">
                                            <td colspan="2">{{ $balance->shop?->shop_name }}</td>
                                            <td>{{ $balance->reference_number}}</td>
                                            <td>{{ $balance->balance_amount -  $balance->collect_amount}}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif

                                {{--  @if($sales->sales_payment)
                                <tr>
                                    <td class="text-center" colspan="3"><h6>New Receive</h6></td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="2"><b>Shop</b></td>
                                    <td><b>Price</b></td>
                                </tr>
                                @foreach ($sales->sales_payment as $payments)
                                @if($payments->cash_type==1)
                                <tr class="text-center">
                                    <td colspan="2">{{ $payments->shop?->shop_name }}</td>
                                    <td>{{ $payments->amount}}</td>
                                </tr>
                                @endif
                                @endforeach
                                @endif  --}}

                                @if($sales->sales_payment->contains('cash_type', 0))
                                <tr>
                                    <td class="text-center" colspan="4"><h6>Check</h6></td>
                                </tr>
                                <tr class="text-center">
                                    <td><b>Shop</b></td>
                                    <td><b>Date</b></td>
                                    <td><b>Memu</b></td>
                                    <td><b>Price</b></td>
                                </tr>
                                @foreach ($sales->sales_payment as $payments)
                                @if($payments->cash_type==0)
                                <tr class="text-center">
                                    <td>{{ $payments->shop?->shop_name }}</td>
                                    <td>{{ $payments->check_date }}</td>
                                    <td>{{ $payments->reference_number }}</td>
                                    <td>{{ $payments->amount}}</td>
                                </tr>
                                @endif
                                @endforeach
                                @endif

                                @if($sales->expenses)
                                <tr class="text-center">
                                    <td  colspan="3"><h6>{{__('Expenses')}}</h6></td>
                                    <td>{{ $sales->expenses}}</td>
                                </tr>
                                @endif
                                @if($sales->commission)
                                <tr class="text-center">
                                    <td  colspan="3"><h6>{{__('Commission')}}</h6></td>
                                    <td>{{ $sales->commission}}</td>
                                </tr>
                                @endif
                                @if($sales->dsr_cash)
                                <tr class="text-center">
                                    <td  colspan="3"><h6>{{__('Dsr Cash Receive')}}</h6></td>
                                    <td>{{ $sales->dsr_cash}}</td>
                                </tr>
                                @endif
                                @if($sales->dsr_salary)
                                <tr class="text-center">
                                    <td  colspan="3"><h6>{{__('DSR Salary')}}</h6></td>
                                    <td>{{ $sales->dsr_salary}}</td>
                                </tr>
                                @endif
                                @if($sales->final_total != $sales->daily_total_taka)
                                <tr class="text-center">
                                    <td class="text-end" colspan="3"><h6>{{__('Final Total')}}</h6></td>
                                    <td class="text-center"><b>{{ $sales->final_total}}</b></td>
                                </tr>
                                <tr class="text-center">
                                    <td class="text-end" colspan="3"><h6>{{__('জমা')}}</h6></td>
                                    <td class="text-center"><b>{{ $sales->final_total}}</b></td>
                                </tr>
                                    @if($sales->final_cash_extra)
                                    <tr class="text-center">
                                        <td class="text-end" colspan="3"><h6>{{__('জমা')}}</h6></td>
                                        <td class="text-center"><b>{{ $sales->final_cash_extra}}</b></td>
                                    </tr>
                                    @endif
                                @endif
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-4 mt-5">
                            <p class="text-center" style="border-top:1px solid black;">
                               Receiver Signature
                            </p>
                        </div>
                        <div class="col-4 mt-5"></div>
                        <div class="col-4 mt-5">
                            <p class="text-center" style="border-top:1px solid black;">
                               Authorized Signature
                            </p>
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
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
@endpush  --}}
