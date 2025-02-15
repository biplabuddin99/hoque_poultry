@extends('layout.app')

@section('pageTitle',trans('Print Sales Closing'))
@section('pageSubTitle',trans('Show'))

@section('content')
<section id="result_show">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" action="{{route(currentUser().'.sales.receive')}}">
                            @csrf
                            <input type="hidden" value="{{ $sales->id }}" name="sales_id">
                            <div class="row p-2 mt-4">
                                @if (!empty($sales->shop_id))
                                <div class="col-lg-3 col-md-3 col-sm-3 mt-2 shopNameContainer">
                                    <label for=""><b>Shop Name</b></label>
                                    <select class="form-select" name="shop_id">
                                        <option value="">Select</option>
                                        @forelse($shops as $sh)
                                        <option value="{{$sh->id}}" {{ $sales->shop_id==$sh->id?"selected":""}}> {{ $sh->shop_name}}</option>
                                        @empty
                                            <option value="">No data found</option>
                                        @endforelse
                                    </select>
                                </div>
                                @endif

                                @if (!empty($sales->dsr_id))
                                    <div class="col-lg-3 col-md-3 col-sm-3 mt-2 dsrNameContainer">
                                        <label for=""><b>DSR Name</b></label>
                                        <select class="form-select" name="dsr_id">
                                            <option value="">Select</option>
                                            @forelse($dsr as $d)
                                            <option value="{{$d->id}}" {{ $sales->dsr_id==$d->id?"selected":""}}> {{ $d->name}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-3 col-md-3 col-sm-3 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input type="text" id="datepicker" class="form-control" value="{{ $sales->sales_date }}"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                            </div>
                            <!-- table bordered -->
                            <div class="row p-2 mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2">{{__('Product Name')}}</th>
                                                <th rowspan="2">{{__('CTN')}}</th>
                                                <th rowspan="2">{{__('PCS')}}</th>
                                                <th colspan="2">{{ __('Return') }}</th>
                                                <th colspan="2" class="text-danger">{{ __('Damage') }}</th>
                                                {{--  <th rowspan="2">{{__('TP/Tp Free')}}</th>  --}}
                                                <th rowspan="2">{{__('Sales(PCS)')}}</th>
                                                <th rowspan="2">{{__('PCS(Price)')}}</th>
                                                {{--  <th rowspan="2">{{__('CTN(Price)')}}</th>  --}}
                                                <th rowspan="2">{{__('Sub-Total(Price)')}}</th>
                                                {{--  <th rowspan="2"></th>  --}}
                                            </tr>
                                            <tr>
                                                <th>CTN</th>
                                                <th>PCS</th>
                                                <th class="text-danger">CTN</th>
                                                <th class="text-danger">PCS</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sales_repeat">

                                            @if ($sales->sales_details)
                                                @foreach ($sales->sales_details as $salesdetails)
                                                @if ($salesdetails->status==0)
                                                <tr>
                                                    <td>
                                                        <input readonly class="form-control" type="text" value="{{ $salesdetails->product?->product_name }}">
                                                    </td>
                                                    <td><input readonly class="form-control ctn" type="text" name="ctn[]" value="{{ old('ctn',$salesdetails->ctn) }}" placeholder="ctn"></td>
                                                    <td><input readonly class="form-control pcs" type="text" name="pcs[]"value="{{ old('pcs',$salesdetails->pcs) }}" placeholder="pcs"></td>
                                                    <td><input class="form-control ctn_return" type="text" name="ctn_return[]" value="{{ old('ctn_return',$salesdetails->ctn_return) }}" placeholder="ctn return"></td>
                                                    <td><input class="form-control pcs_return" type="text" name="pcs_return[]"value="{{ old('pcs_return',$salesdetails->pcs_return) }}" placeholder="pcs return"></td>
                                                    <td><input class="form-control ctn_damage" type="text" name="ctn_damage[]" value="{{ old('ctn_damage',$salesdetails->ctn_damage) }}" placeholder="ctn damage"></td>
                                                    <td><input class="form-control pcs_damage" type="text" name="pcs_damage[]"value="{{ old('pcs_damage',$salesdetails->pcs_damage) }}" placeholder="pcs damage"></td>
                                                    <td><input class="form-control total_sales_pcs" type="text" name="total_sales_pcs[]" value="{{ old('total_sales_pcs',$salesdetails->total_sales_pcs) }}"></td>
                                                    {{--  <td style="width: 110px;">
                                                        <select class="form-select" name="select_tp_tpfree">
                                                            <option value="">Select</option>
                                                            <option value="1" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="1" ? "selected":""}}>TP</option>
                                                            <option value="2" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="2" ? "selected":""}}>TP Free</option>
                                                        </select>
                                                    </td>  --}}
                                                    <td>
                                                        <input readonly class="form-control per_pcs_price" type="text" name="pcs_price[]" @if($salesdetails->tp_price) value="{{ old('pcs_price',$salesdetails->tp_price) }}"@else value="{{ old('pcs_price',$salesdetails->tp_free) }}" @endif>
                                                    </td>
                                                    {{--  <td><input class="form-control" type="text" name="ctn_price[]" value="{{ old('ctn_price',$salesdetails->ctn_price) }}" placeholder="Ctn Price"></td>  --}}
                                                    <td><input readonly class="form-control subtotal_price" type="text" name="subtotal_price[]" value="{{ old('subtotal_price',$salesdetails->subtotal_price) }}" placeholder="Sub total"></td>
                                                    {{--  <td></td>  --}}
                                                </tr>
                                                @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td class="text-end" colspan="9"><h5 for="totaltk">{{__('Total Taka')}}</h5></td>
                                                <td class="text-end" colspan="10">
                                                    <input type="text" class="form-control ptotal_taka" value="{{ $sales->daily_total_taka }}" name="total_taka">
                                                    {{--  <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>  --}}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot id="tfootSection">
                                            @if ($sales->sales_details)
                                            @foreach ($sales->sales_details as $salesdetails)
                                            @if ($salesdetails->status==1)
                                            <tr>
                                                <td colspan="3">
                                                    <input readonly class="form-control" type="text" value="{{ $salesdetails->product?->product_name }}">
                                                </td>
                                                <td><input class="form-control old_ctn" type="text" name="old_ctn_return[]" value="{{ old('old_ctn_return',$salesdetails->ctn_return) }}" placeholder="ctn return"></td>
                                                <td><input class="form-control old_pcs" type="text" name="old_pcs_return[]" value="{{ old('old_pcs_return',$salesdetails->pcs_return) }}" placeholder="pcs return"></td>
                                                <td><input class="form-control old_ctn_damage" type="text" name="old_ctn_damage[]" value="{{ old('old_ctn_damage',$salesdetails->ctn_damage) }}" placeholder="ctn damage"></td>
                                                <td><input class="form-control old_pcs_damage" type="text" name="old_pcs_damage[]" value="{{ old('old_pcs_damage',$salesdetails->pcs_damage) }}" placeholder="pcs damage"></td>
                                                {{--  <td>
                                                    <select class="form-select" name="select_tp_tpfree">
                                                        <option value="">Select</option>
                                                        <option value="1">TP</option>
                                                        <option value="2">TP Free</option>
                                                    </select>
                                                </td>  --}}
                                                <td> <input readonly class="form-control" type="text" value="" placeholder="PCS"></td>
                                                <td>
                                                    <input class="form-control old_pcs_price" type="text" name="old_pcs_price[]" value="{{ $salesdetails->tp_price }}" placeholder="PCS Price">
                                                </td>
                                                <td><input class="form-control" type="text" name="return_subtotal_price[]" value="{{ $salesdetails->subtotal_price }}" placeholder="Sub total"></td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            @endif
                                            @if ($salesdetails->status==1)
                                            <tr>
                                                <td class="text-end" colspan="9"><h5 for="return_total">{{__('Return Total Taka')}}</h5></td>
                                                <td class="text-end" colspan="10">
                                                    <input readonly type="text" class="form-control return_total_taka" value="{{ $sales->return_total_taka }}" name="return_total_taka">
                                                </td>
                                            </tr>
                                            @endif
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    @if($sales->shop_balance)
                                    @foreach ($sales->shop_balance as $balance)
                                    @if($balance->status==0)
                                    <div class="row olddue">
                                        <div class="col-lg-2 col-md-3 col-sm-2">
                                            <div class="form-group">
                                                <h5 for="check">{{__('Old Due')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-4">
                                            <input readonly class="form-control" type="text" value="{{ $balance->shop?->shop_name }}">
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('old_due_tk',$balance->balance_amount)}}" name="old_due_tk[]">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                    <hr>
                                    @if($sales->shop_balance)
                                    @foreach ($sales->shop_balance as $balance)
                                    @if($balance->status==1)
                                    <div class="row newdue">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('New Due')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <input readonly class="form-control" type="text" value="{{ $balance->shop?->shop_name }}">
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('new_due_tk',$balance->balance_amount)}}" name="new_due_tk[]">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                    <hr>

                                    @if($sales->sales_payment)
                                    @foreach ($sales->sales_payment as $payments)
                                    @if($payments->cash_type==1)
                                    <div class="row new_receive">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('New Receive')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <input readonly class="form-control" type="text" value="{{ $payments->shop?->shop_name }}">
                                            {{--  <select class="form-select new_receive_shop_id" name="new_receive_shop_id[]">
                                                <option value="">Select</option>
                                                @foreach (\App\Models\Settings\Shop::all(); as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                @endforeach
                                            </select>  --}}
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control new_receive_tk" onkeyup="totalNewReceive()" value="{{ old('new_receive_tk',$payments->amount)}}" name="new_receive_tk[]" placeholder="Tk">
                                                <input type="hidden" class="form-control n_receive_tk" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif

                                    @if($sales->sales_payment)
                                    @foreach ($sales->sales_payment as $payments)
                                    @if($payments->cash_type==0)
                                    <div class="row check_no">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('Check')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <input readonly class="form-control" type="text" value="{{ $payments->shop?->shop_name }}">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input readonly type="text" class="form-control check_shop_tk" onkeyup="totalNewCheck()" value="{{ old('check_shop_tk',$payments->amount)}}" name="check_shop_tk[]">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control" value="{{ old('check_date',$payments->check_date)}}" name="check_date[]" placeholder="Date">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif

                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <h5 for="expenses">{{__('Expenses')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-9 col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control expenses_tk" value="{{ old('expenses',$sales->expenses)}}" name="expenses">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <h5 for="commission">{{__('Commission')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-9 col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control commission_tk" value="{{ old('commission',$sales->commission)}}" name="commission">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <h5 for="total">{{__('Final Total')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-9 col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control final_total_tk" value="{{ old('final_total',$sales->final_total)}}" name="final_total">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end my-2">
                                {{--  <button type="submit" class="btn btn-primary">Save</button>  --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
@endsection
@push('scripts')
<script>
    function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
@endpush
