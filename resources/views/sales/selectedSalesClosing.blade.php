@extends('layout.app')

@section('pageTitle',trans('Selected Closing'))
@section('pageSubTitle',trans('Return'))

@section('content')
<style>
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 100% !important;
    }
    .displaySection .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 100% !important;
    }
   
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" action="{{route(currentUser().'.sales.receive')}}" onsubmit="return confirm('are you sure Can not receive later?')">
                            @csrf
                            <input type="hidden" value="{{ $sales?->id }}" name="tem_sales_id">
                            <div class="row p-2 mt-4">
                                @if (!empty($sales->distributor_id))
                                    <div class="col-lg-3 col-md-3 col-sm-6 mt-2 dsrNameContainer">
                                        <label for=""><b>Distributor Name</b></label>
                                        <input readonly class="form-control" type="text" value="{{ $sales->distributor?->name }}" placeholder="">
                                        <input class="form-control" type="hidden" name="distributor_id" value="{{ old('distributor_id',$sales->distributor_id) }}" placeholder="">
                                    </div>
                                @endif
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>SR Name</b></label>
                                    <input readonly type="text" id="" class="form-control" value="{{ $sales->sr?->name }}">
                                    <input class="form-control" type="hidden"  name="sr_id" value="{{ $sales->sr_id }}" placeholder="">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>Area</b></label>
                                    <input readonly type="text" class="form-control" value="{{ $sales->area?->name }}">
                                    <input class="form-control" type="hidden"  name="area_id" value="{{ $sales->area_id }}" placeholder="">
                                </div>
                                @if (!empty($sales->shop_id))
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2 shopNameContainer">
                                    <label for=""><b>Shop Name</b></label>
                                    <input readonly class="form-control" type="text" value="{{ $sales->shop?->shop_name }}" placeholder="">
                                    <input class="form-control" type="hidden" name="shop_id" value="{{ old('shop_id',$sales->shop_id) }}" placeholder="">
                                </div>
                                @endif

                                @if (!empty($sales->dsr_id))
                                    <div class="col-lg-3 col-md-3 col-sm-6 mt-2 dsrNameContainer">
                                        <label for=""><b>DSR Name</b></label>
                                        <input readonly class="form-control" type="text" value="{{ $sales->dsr?->name }}" placeholder="">
                                        <input class="form-control" type="hidden" name="dsr_id" value="{{ old('dsr_id',$sales->dsr_id) }}" placeholder="">
                                    </div>
                                @endif
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input readonly type="text" id="datepicker" class="form-control" value="{{ $sales?->sales_date }}"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                            </div>
                            <!-- table bordered -->
                            <div class="row p-2 mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2">{{__('SL')}}</th>
                                                <th rowspan="2" width="25%">{{__('Product Name')}}</th>
                                                <th rowspan="2">{{__('CTN')}}</th>
                                                <th rowspan="2">{{__('PCS')}}</th>
                                                <th colspan="2">{{ __('Return') }}</th>
                                                <th colspan="2" class="text-danger">{{ __('Damage') }}</th>
                                                {{--  <th rowspan="2">{{__('TP/Tp Free')}}</th>  --}}
                                                <th rowspan="2">{{__('PCS(Price)')}}</th>
                                                {{--  <th rowspan="2">{{__('CTN(Price)')}}</th>  --}}
                                                <th rowspan="2">{{__('Sub-Total(Price)')}}</th>
                                                <th rowspan="2"></th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>CTN</th>
                                                <th>PCS</th>
                                                {{-- <th class="text-danger">CTN</th> --}}
                                                <th class="text-danger">PCS</th>
                                                <th class="text-danger">Total Pcs</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $sl=0;
                                        @endphp
                                        <tbody id="sales_repeat">
                                            @if ($sales?->temporary_sales_details)
                                                @foreach ($sales?->temporary_sales_details as $salesdetails)
                                                    <tr>
                                                        <td>{{++$sl}}</td>
                                                        <td>
                                                            <input readonly class="form-control" type="text" value="{{ $salesdetails->product?->product_name }}">
                                                            <input readonly class="form-control product_id" type="hidden" name="product_id[]" value="{{ $salesdetails->product_id }}">
                                                            <input readonly class="form-control group_id" type="hidden" name="group_id[]" value="{{ $salesdetails->group_id }}">
                                                            {{--  <select class="choices form-select product_id" id="product_id" onchange="doData(this);" name="product_id[]">
                                                                <option value="">Select Product</option>
                                                                @forelse (\App\Models\Product\Product::where(company())->get(); as $pro)
                                                                <option data-dp='{{ $pro->dp_price }}' value="{{ $pro->id }}" {{ old('product_id', $pro->id)==$salesdetails->product_id ? "selected":""}}>{{ $pro->product_name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>  --}}
                                                        </td>
                                                        <td><input readonly class="form-control ctn" type="text" name="ctn[]" value="{{ old('ctn',$salesdetails->ctn) }}" ></td>
                                                        <td><input readonly class="form-control pcs" type="text" name="pcs[]"value="{{ old('pcs',$salesdetails->pcs) }}" ></td>
                                                        <td><input class="form-control ctn_return" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="ctn_return[]" value=""></td>
                                                        <td><input class="form-control pcs_return" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="pcs_return[]"value="" ></td>
                                                        <td><input class="form-control pcs_damage" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="pcs_damage[]"value="" ></td>
                                                        <td><input class="form-control ctn_damage" type="hidden" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="ctn_damage[]" value="" ><input type="text" class="form-control actual_total_pcs" value="{{$salesdetails->totalquantity_pcs}}"></td>
                                                        {{--  <td style="width: 110px;">
                                                            <select class="form-select" name="select_tp_tpfree">
                                                                <option value="">Select</option>
                                                                <option value="1" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="1" ? "selected":""}}>TP</option>
                                                                <option value="2" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="2" ? "selected":""}}>TP Free</option>
                                                            </select>
                                                        </td>  --}}
                                                        <td>
                                                            <input readonly class="form-control per_pcs_price" type="text" name="pcs_price[]" value="{{ old('pcs_price',$salesdetails->pcs_price) }}">
                                                            <input class="form-control select_tp_tpfree" type="hidden" name="select_tp_tpfree[]" value="{{ $salesdetails->select_tp_tpfree }}">
                                                            @if($salesdetails->select_tp_tpfree==1)
                                                                <input class="form-control" type="hidden" name="price_type[]" value="1">
                                                            @else
                                                                <input class="form-control" type="hidden" name="price_type[]" value="2">
                                                            @endif
                                                            <input class="form-control" type="hidden" name="tp_price[]" value="{{ old('tp_price',$salesdetails->pcs_price) }}">

                                                            <input class="form-control total_return_pcs" type="hidden" name="total_return_pcs[]" value="">
                                                            <input class="form-control total_damage_pcs" type="hidden" name="total_damage_pcs[]" value="">
                                                            <input class="form-control total_sales_pcs" type="hidden" name="total_sales_pcs[]" value="{{ $salesdetails->totalquantity_pcs }}">
                                                        </td>
                                                        {{--  <td><input class="form-control" type="text" name="ctn_price[]" value="{{ old('ctn_price',$salesdetails->ctn_price) }}" placeholder="Ctn Price"></td>  --}}
                                                        <td><input readonly class="form-control subtotal_price" type="text" name="subtotal_price[]" value="{{ old('subtotal_price',$salesdetails->subtotal_price) }}"></td>
                                                        <td></td>
                                                    </tr>

                                                @endforeach
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-end">Total</th>
                                                <th id="totalCtn" class="text-center"></th>
                                                <th id="totalPcs" class="text-center"></th>
                                            </tr>
                                            <tr>
                                                <td class="text-end" colspan="8"><h5 for="totaltk">{{__('Total Taka')}}</h5></td>
                                                <td class="text-end">
                                                    <input type="text" class="form-control ptotal_taka" value="{{ $sales->total }}" name="daily_total_taka">
                                                    <span onClick='previousProduct();' class="add-row text-primary" style="font-size:1.2rem"><i class="bi bi-plus-square-fill"></i></span>
                                                    <span onClick='displayProduct();' class="add-row text-info" style="font-size:1.2rem"><i class="bi bi-plus-square-fill"></i></span>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot id="tfootSection" style="display: none;">
                                            <tr>
                                                <td class="text-end" colspan="8"><h5 for="return_total">{{__('Return Total Taka')}}</h5></td>
                                                <td class="text-end">
                                                    <input readonly type="text" class="form-control return_total_taka" value="0" name="return_total_taka">
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <table class="table table-bordered mb-0 ">
                                        <tbody class="displaySection" id="displaySection" style="display: none;">
                                            <tr><th colspan="9" class="text-start"><h6>Display Product</h6></th></tr>
                                        </tbody>
                                        {{-- <tfoot id="displayFootSection" style="display: none;">
                                            <tr>
                                                <td class="text-end" colspan="7"><h5 for="">{{__('Display Total Taka')}}</h5></td>
                                                <td class="text-end">
                                                    <input readonly type="text" class="form-control display_total_taka" value="0" name="display_total_taka">
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-center border-end">
                                    <b>Note and Coin</b>
                                    <table class="ms-3" width="auto" cellspcing="0">
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>1</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control onetaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="onetakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>2</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control twotaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="twotakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>5</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control fivetaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="fivetakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>10</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control tentaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="tentakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>20</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control twentytaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="twentytakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>50</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control fiftytaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="fiftytakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>100</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control onehundredtaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="onehundredtakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>200</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control twohundredtaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="twohundredtakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>500</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control fivehundredtaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="fivehundredtakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-info text-white px-3 text-center"><b>1000</b></td>
                                            <td><input style="width:100px;" onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control onethousandtaka" type="number" /></td>
                                            <th class="ps-1"> = </th>
                                            <th class="onethousandtakaCalculate">0</th>
                                        </tr>
                                        <tr>
                                            <td class="text-white px-3 text-center"></td>
                                            <th>Total</th>
                                            <th class="ps-1"> =
                                                <input type="hidden" class="today_final_cash" value="" name="today_final_cash">
                                            </th>
                                            <th class="allcoinNot">0</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Final Total</th>
                                            <th class="ps-1"> = <input style="width:110px;" disabled type="hidden" class="form-control final_total_tk" value="{{ old('final_total')}}"></th>
                                            <th class="allConinUpdate"></th>
                                            <input type="hidden" class="final_cash_extra" value="" name="final_cash_extra">
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-9">
                                  {{--  <div class="row">
                                      <div class="col-lg-3 col-md-3 col-sm-4">
                                          <div class="form-group">
                                              <h5 for="totaltk">{{__('Total Taka')}}</h5>
                                          </div>
                                      </div>
                                      <div class="col-lg-9 col-md-9 col-sm-8">
                                          <div class="form-group">
                                              <input type="text" class="form-control" value="{{ old('total_tk')}}" name="total_tk">
                                          </div>
                                      </div>
                                  </div>  --}}
                                  @forelse ($shopDue as $due)
                                    <div class="row olddue">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('Old Due')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="select2 form-select old_due_shop_id shop_main" name="old_due_shop_id[]" onchange="checkOldDueRequired(this);">
                                                <option value="">Select</option>
                                                @foreach ($shops as $shop)
                                                    <option value="{{ $shop->id }}" {{$due->old_due_shop_id==$shop->id?'selected': ''}}>{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                                                @endforeach
                                            </select>
                                            <a data-cat-id="3" href="#" data-bs-toggle="modal" data-bs-target="#shopName" class="text-primary mx-1" title="Job Head"><i class="bi bi-plus-square-fill"></i></a>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control old_due_tk" value="{{$due->due_amount}}" onkeyup="totalOldDue();FinalTotal();" onblur="totalOldDue();FinalTotal();" onchange="totalOldDue();FinalTotal();" value="{{ old('old_due_tk')}}" name="old_due_tk[]" placeholder="Tk">
                                                <input type="hidden" class="form-control o_due_tk" value="{{$due->due_amount}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control old_due_date" value="{{ old('old_due_date')}}" name="old_due_date[]" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                <span onClick='oldDue();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                  @empty
                                    <div class="row olddue">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('Old Due')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="select2 form-select old_due_shop_id shop_main" name="old_due_shop_id[]" onchange="checkOldDueRequired(this);">
                                                <option value="">Select</option>
                                                @foreach ($shops as $shop)
                                                    <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                                                @endforeach
                                            </select>
                                            <a data-box="1" data-cat-id="3" href="#" data-bs-toggle="modal" data-bs-target="#shopName" class="text-primary mx-1" title="Job Head"><i class="bi bi-plus-square-fill"></i></a>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control old_due_tk" onkeyup="totalOldDue();FinalTotal();" onblur="totalOldDue();FinalTotal();" onchange="totalOldDue();FinalTotal();" value="{{ old('old_due_tk')}}" name="old_due_tk[]" placeholder="Tk">
                                                <input type="hidden" class="form-control o_due_tk" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control old_due_date" value="{{ old('old_due_date')}}" name="old_due_date[]" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                <span onClick='oldDue();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                  @endforelse
                                    <hr>
                                    <div class="row newdue">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('new Due')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="select2 form-select new_due_shop_id shop_main" name="new_due_shop_id[]" onchange="checkNewDueRequired(this);">
                                                <option value="">Select</option>
                                                @foreach ($shops as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                                                @endforeach
                                            </select>
                                            <a data-box="2" data-cat-id="3" href="#" data-bs-toggle="modal" data-bs-target="#shopName" class="text-primary mx-1" title="Job Head"><i class="bi bi-plus-square-fill"></i></a>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control new_due_tk" onkeyup="totalNewDue();FinalTotal();" onblur="totalNewDue();FinalTotal();" onchange="totalNewDue();FinalTotal();" value="{{ old('new_due_tk')}}" name="new_due_tk[]" placeholder="Tk">
                                                <input type="hidden" class="form-control n_due_tk" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control new_due_date" value="{{ old('new_due_date')}}" name="new_due_date[]" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                <span onClick='newDue();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    {{--  <div class="row new_receive">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('New Receive')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="form-select new_receive_shop_id" name="new_receive_shop_id[]">
                                                <option value="">Select</option>
                                                @foreach (\App\Models\Settings\Shop::all(); as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control new_receive_tk" onkeyup="totalNewReceive();FinalTotal();" onblur="totalNewReceive();FinalTotal();" onchange="totalNewReceive();FinalTotal();" value="{{ old('new_receive_tk')}}" name="new_receive_tk[]" placeholder="Tk">
                                                <input type="hidden" class="form-control n_receive_tk" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                <span onClick='newReceive();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>  --}}
                                    <div class="row check_no">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('Check')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="select2 form-select check_shop_id shop_main" name="check_shop_id[]" onchange="checkDueRequired(this);">
                                                <option value="">Select</option>
                                                @foreach ($shops as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                                                @endforeach
                                            </select>
                                            <a data-box="3" data-cat-id="3" href="#" data-bs-toggle="modal" data-bs-target="#shopName" class="text-primary mx-1" title="Job Head"><i class="bi bi-plus-square-fill"></i></a>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="check_number[]" class="form-control check_number" placeholder="Check Number">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control check_shop_tk" onkeyup="totalNewCheck();FinalTotal();" onblur="totalNewCheck();FinalTotal();" onchange="totalNewCheck();FinalTotal();" value="{{ old('check_shop_tk')}}" name="check_shop_tk[]" placeholder="Tk">
                                                <input type="hidden" class="form-control c_shop_tk" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control check_date" value="{{ old('check_date')}}" name="check_date[]" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                <span onClick='newCheck();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <h5 for="expenses">{{__('Expenses')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-9 col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control expenses_tk" onkeyup="FinalTotal()" onblur="FinalTotal()" onchange="FinalTotal()" value="{{ old('expenses')}}" name="expenses">
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
                                                <input type="text" class="form-control commission_tk" onkeyup="FinalTotal()" onblur="FinalTotal()" onchange="FinalTotal()" value="{{ old('commission')}}" name="commission">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <h5 for="cash">{{__('Dsr Cash Receive')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-9 col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control cash" value="{{$sales->receive_amount}}" onkeyup="FinalTotal()" onblur="FinalTotal()" onchange="FinalTotal()" value="{{ old('cash')}}" name="cash">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <h5 for="dsr_salary">{{__('DSR Salary')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-9 col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control dsr_salary" onkeyup="FinalTotal()" onblur="FinalTotal()" onchange="FinalTotal()" value="{{ old('dsr_salary')}}" name="dsr_salary">
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
                                                <input readonly type="text" class="form-control final_total_tk" value="{{ old('final_total')}}" name="final_total">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end my-2">
                                <button type="submit" id="savebutton" disabled class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <div class="modal fade" id="shopName" tabindex="-1" role="dialog" aria-labelledby="shopName" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Shop Name</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input type="hidden" id="category-id" name="category">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sup_id">Distributor<span class="text-danger">*</span></label>
                                    <select class="form-select border border-primary" name="sup_id" onchange="srShow(this.value);" required  id="sup_id">
                                        <option value="">Select</option>
                                        @forelse (App\Models\Settings\Supplier::where(company())->get() as $sup)
                                            <option value="{{ $sup->id }}" {{old('sup_id',$sales->distributor_id)==$sup->id ? 'selected' : ''}}>{{ $sup->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sr_id">SR <span class="text-danger">*</span></label>
                                    <select class="form-select border border-primary" name="sr_id" id="srUser_id">
                                        <option value="">Select</option>
                                        @forelse (\App\Models\User::where(company())->where('role_id',5)->get() as $sr)
                                            <option class="selecet_hide selecet_hide{{$sr->distributor_id}}" value="{{ $sr->id }}" {{old('sr_id',$sales->sr_id)==$sr->id ? 'selected': ''}}>{{ $sr->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_name">Area Name</label>
                                    <select name="area_name" class="form-control form-select" id="area_id">
                                            <option value="">Select area</option>
                                            @foreach ($area as $a)
                                                <option class="selecet_hide selecet_hide{{$a->distributor_id}}" value="{{$a->id}}" {{old('area_id',$sales->area_id)==$a->id ? 'selected': ''}}>{{$a->name}}</option>
                                            @endforeach
                                        </select>
                                    {{-- <input type="text" name="area_name" class="form-control" id="area_name"
                                        placeholder="Enter a name" /> --}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_name">Shop Name</label>
                                    <input type="text" name="shop_name" class="form-control shop_name" id="shop_name"
                                        placeholder="Enter a name" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_name">Owner Name</label>
                                    <input type="text" name="owner_name" class="form-control" id="owner_name"
                                        placeholder="Enter a name" />
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_name">Contact</label>
                                    <input type="text" name="contact" class="form-control" id="contact"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_name">Address</label>
                                    <input type="text" name="address" class="form-control" id="address"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_name">Balance</label>
                                    <input type="text" name="balance" class="form-control" id="balance"
                                        placeholder="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="save-option-btn" onclick="shopSave()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Add New Shop</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
<script>

    FinalTotal();
    function previousProduct(){
        var tfootSection = document.getElementById('tfootSection');
        tfootSection.style.display = 'table-row-group';
        var previousProduct=`
            <tr>
                {{--  <td>
                    <select class=" group_product" id="group_id" name="group_id[]" onchange="groupWiseProductShow(this);">
                        <option value="">Select Group</option>
                        @forelse ($group as $pg)
                            <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </td>  --}}
                <td colspan="3">
                    <input readonly class="form-control" type="hidden" name="group_id_rtn[]" value="{{ $salesdetails->group_id }}">
                    <select class="select2 form-select product_id"  onchange="getCtnQty(this);" name="return_product_id[]">
                        <option value="">Select Product</option>
                        @forelse ($product as $pro)
                        <option data-dp='{{ $pro->dp_price }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td><input class="form-control old_ctn" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_ctn_return[]" value="" placeholder="ctn return"></td>
                <td><input class="form-control old_pcs" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_pcs_return[]" value="" placeholder="pcs return"></td>
                <td><input class="form-control old_pcs_damage" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_pcs_damage[]" value="" placeholder="pcs damage"></td>
                <td>
                    <input class="form-control old_ctn_damage" type="hidden" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_ctn_damage[]" value="" placeholder="ctn damage">
                    <input class="form-control old_actual_total_pcs" type="text" placeholder="Total pcs">
                </td>
                {{--  <td>
                    <select class="form-select" name="select_tp_tpfree">
                        <option value="">Select</option>
                        <option value="1">TP</option>
                        <option value="2">TP Free</option>
                    </select>
                </td>  --}}
                <td>
                    <input class="form-control old_pcs_price" type="text" onkeyup="getCtnQty(this);" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_pcs_price[]" value="" placeholder="PCS Price">
                    <input class="form-control old_total_return_pcs" type="hidden" name="old_total_return_pcs[]" value="">
                    <input class="form-control old_total_damage_pcs" type="hidden" name="old_total_damage_pcs[]" value="">
                </td>
                {{--  <td><input class="form-control" type="text" name="ctn_price[]" value="" placeholder="Ctn Price"></td>  --}}
                <td><input class="form-control return_subtotal_price" type="text" onkeyup="return_total_calculate();" onblur="return_total_calculate();" onchange="return_total_calculate();" name="return_subtotal_price[]" value="" placeholder="Sub total"></td>
                <td>
                    <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                </td>
            </tr>`;
            $('#sales_repeat').append(previousProduct);
            $('.select2').select2();
    }

function removeRow(e){
    if (confirm("Are you sure you want to remove this row?")) {
    $(e).closest('tr').remove();
    return_total_calculate();
    FinalTotal();
    }
}
function displayProduct(){
    // var displayFootSection = document.getElementById('displayFootSection');
    var displayBodySection = document.getElementById('displaySection');
    displayBodySection.style.display = 'table-row-group';
    // displayFootSection.style.display = 'table-row-group';
    var displayProduct=`
        <tr>
            <td width="30%" colspan="2">
                <input readonly class="form-control" type="hidden" name="group_id_display_product[]" value="{{ $salesdetails->group_id }}">
                <select class="select2 form-select product_id" name="display_product_id[]" onchange="displayProductPrice(this);">
                    <option value="">Select Product</option>
                    @forelse ($product as $pro)
                    <option data-tp-free='{{ $pro->tp_free }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                    @empty
                    @endforelse
                </select>
            </td>
            
            <td width="20%" colspan="3"><input class="form-control" type="text" name="display_shop_name[]" placeholder="shop name"></td>
            <td><input class="form-control display_product_qty" type="number" onkeyup="displayProductCount(this);" name="display_product_qty[]" placeholder="qty"></td>
            <td><input class="form-control display_product_price" type="text" name="display_product_price[]" placeholder="Price" readonly></td>
            <td><input class="form-control display_total_price" type="text" name="display_total_price[]" placeholder="total Price" readonly></td>
            <td>
                <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
            </td>
        </tr>`;
        $('#displaySection').append(displayProduct);
        $('.select2').select2();
}

function displayProductPrice(e){
    var tpFree = $(e).closest('tr').find('.product_id option:selected').attr('data-tp-free');
    $(e).closest('tr').find('.display_product_price').val(tpFree);
}
function displayProductCount(e){
    var qty = $(e).closest('tr').find('.display_product_qty').val()?parseFloat($(e).closest('tr').find('.display_product_qty').val()):0;
    var price = $(e).closest('tr').find('.display_product_price').val()?parseFloat($(e).closest('tr').find('.display_product_price').val()):0;
    var totalPrice = qty*price;
    $(e).closest('tr').find('.display_total_price').val(totalPrice);
}

function return_total_calculate() {
    var subtotal = 0;
    $('.return_subtotal_price').each(function() {
        subtotal += isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
    });
    // $('.total').text(parseFloat(subtotal).toFixed(2));
    $('.return_total_taka').val(parseFloat(subtotal).toFixed(2));

}
function primarySubTotal() {
    var psubtotal = 0;
    $('.subtotal_price').each(function() {
        psubtotal += isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
    });
    $('.ptotal_taka').val(parseFloat(psubtotal).toFixed(2));

}
function totalOldDue() {
    var tolddue = 0;
    $('.old_due_tk').each(function() {
        tolddue += isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
    });

    $('.o_due_tk').val(parseFloat(tolddue).toFixed(2));
}
function totalNewDue() {
    var toNwdue = 0;
    $('.new_due_tk').each(function() {
        toNwdue += isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
    });
    $('.n_due_tk').val(parseFloat(toNwdue).toFixed(2));

}
function totalNewReceive() {
    var toNwRec = 0;
    $('.new_receive_tk').each(function() {
        toNwRec += isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
    });
    $('.n_receive_tk').val(parseFloat(toNwRec).toFixed(2));

}
function totalNewCheck() {
    var toNwCk = 0;
    $('.check_shop_tk').each(function() {
        toNwCk += isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
    });
    $('.c_shop_tk').val(parseFloat(toNwCk).toFixed(2));

}

function FinalTotal(){
    var todayTotal=parseFloat($('.ptotal_taka').val());
    var dsrCashTk=parseFloat($('.cash').val());
    var dsrSalary=parseFloat($('.dsr_salary').val());
    var returnTotal=parseFloat($('.return_total_taka').val());
    var oldDue=parseFloat($('.o_due_tk').val());
    console.log(returnTotal);
    var newDue=parseFloat($('.n_due_tk').val());
    var newRec=parseFloat($('.n_receive_tk').val());
    var newCheck=parseFloat($('.c_shop_tk').val());
    console.log(newCheck);
    var expenses=parseFloat($('.expenses_tk').val());
    var comission=parseFloat($('.commission_tk').val());

    if(todayTotal)todayTotal=todayTotal; else todayTotal=0;
    if(returnTotal)returnTotal=returnTotal; else returnTotal=0;
    if(oldDue)oldDue=oldDue; else oldDue=0;
    if(newDue)newDue=newDue; else newDue=0;
    if(newRec)newRec=newRec; else newRec=0;
    if(newCheck)newCheck=newCheck; else newCheck=0;
    if(expenses)expenses=expenses; else expenses=0;
    if(comission)comission=comission; else comission=0;
    if(dsrCashTk)dsrCashTk=dsrCashTk; else dsrCashTk=0;
    if(dsrSalary)dsrSalary=dsrSalary; else dsrSalary=0;

    var total= ((todayTotal+oldDue+dsrCashTk)-(returnTotal+newDue+expenses+comission+newCheck+dsrSalary));
    //var total= (todayTotal-(returnTotal+expenses+comission));
    $('.final_total_tk').val(Math.round(parseFloat(total)));
    getCoinNote();
}

function oldDue(){
    var oldDue=`
    <div class="row append_remove m-0 p-0">
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <h5 for="check">{{__('Old Due')}}</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
            <select class="select2 form-select old_due_shop_id shop_data" name="old_due_shop_id[]" onchange="checkOldDueRequired(this);">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control old_due_tk" onkeyup="totalOldDue();FinalTotal();" onblur="totalOldDue();FinalTotal();" onchange="totalOldDue();FinalTotal();" value="{{ old('old_due_tk')}}" name="old_due_tk[]" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="date" class="form-control old_due_date" value="{{ old('old_due_date')}}" name="old_due_date[]" placeholder="Date">
            </div>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeOld(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
            </div>
        </div>
    </div>
    `;
    $('.olddue').append(oldDue);
    $('.select2').select2();
    populateAllShopSelectBoxes();
}
function removeOld(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
        totalOldDue();
        FinalTotal();
    }
}
function newDue(){
    var newDue=`
    <div class="row appendnew_remove m-0 p-0">
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <h5 for="check">{{__('new Due')}}</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
            <select class="select2 form-select new_due_shop_id shop_data" name="new_due_shop_id[]" onchange="checkNewDueRequired(this);">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control new_due_tk" onkeyup="totalNewDue();FinalTotal();" onblur="totalNewDue();FinalTotal();" onchange="totalNewDue();FinalTotal();" value="{{ old('new_due_tk')}}" name="new_due_tk[]" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="date" class="form-control new_due_date" value="{{ old('new_due_date')}}" name="new_due_date[]" placeholder="Date">
            </div>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeNewDue(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                 {{--  <span onClick='newDue();'><i class="bi bi-plus-square-fill"></i></span>  --}}
            </div>
        </div>
    </div>
    `;
    $('.newdue').append(newDue);
    $('.select2').select2();
    populateAllShopSelectBoxes();
}
function removeNewDue(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
        totalNewDue();
        FinalTotal();
    }
}

function newReceive(){
    var newReceive=`
    <div class="row append_new_receive m-0 p-0">
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <h5 for="check">{{__('New Receive')}}</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
            <select class="form-select new_receive_shop_id" name="new_receive_shop_id[]">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control new_receive_tk" onkeyup="totalNewReceive();FinalTotal();" onblur="totalNewReceive();FinalTotal();" onchange="totalNewReceive();FinalTotal();" value="{{ old('new_receive_tk')}}" name="new_receive_tk[]" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeNewRec(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                 {{--  <span onClick='newReceive();'><i class="bi bi-plus-square-fill"></i></span>  --}}
            </div>
        </div>
    </div>`;
    $('.new_receive').append(newReceive);
}
function removeNewRec(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
        totalNewReceive();
        FinalTotal();
    }
}
function newCheck(){
    var newCheck=`
    <div class="row append_check m-0 p-0">
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <h5 for="check">{{__('Check')}}</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 shopNameContainer">
            <select class="select2 form-select check_shop_id shop_data" name="check_shop_id[]" onchange="checkDueRequired(this);">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}-{{$shop->area?->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" name="check_number[]" class="form-control check_number" placeholder="Check Number">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control check_shop_tk" onkeyup="totalNewCheck();FinalTotal();" onblur="totalNewCheck();FinalTotal();" onchange="totalNewCheck();FinalTotal();" value="{{ old('check_shop_tk')}}" name="check_shop_tk[]" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="date" class="form-control check_date" value="{{ old('check_date')}}" name="check_date[]" placeholder="Date">
            </div>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeNewCheck(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                 {{--  <span onClick='newCheck();'><i class="bi bi-plus-square-fill"></i></span>  --}}
            </div>
        </div>
    </div>`;
    $('.check_no').append(newCheck);
    $('.select2').select2();
    populateAllShopSelectBoxes();
}
function removeNewCheck(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
        totalNewCheck();
        FinalTotal();
    }
}

function checkNewDueRequired(e) {
    let selectedValue = $(e).val();
    console.log(selectedValue);
    if (selectedValue) {
        //$(e).closest('.row').find('.new_due_date').attr('required', true);
        $(e).closest('.row').find('.new_due_tk').attr('required', true);
    } else {
        //$(e).closest('.row').find('.new_due_date').removeAttr('required');
        $(e).closest('.row').find('.new_due_tk').removeAttr('required');
    }
}
function checkOldDueRequired(e) {
    let selectedValue = $(e).val();
    console.log(selectedValue);
    if (selectedValue) {
        //$(e).closest('.row').find('.old_due_date').attr('required', true);
        $(e).closest('.row').find('.old_due_tk').attr('required', true);
    } else {
        //$(e).closest('.row').find('.old_due_date').removeAttr('required');
        $(e).closest('.row').find('.old_due_tk').removeAttr('required');
    }
}

function checkDueRequired(e) {
    let selectedValue = $(e).val();
    console.log(selectedValue);
    if (selectedValue) {
        $(e).closest('.row').find('.check_shop_tk').attr('required', true);
        $(e).closest('.row').find('.check_number').attr('required', true);
        $(e).closest('.row').find('.check_date').attr('required', true);
    } else {
        $(e).closest('.row').find('.check_shop_tk').removeAttr('required');
        $(e).closest('.row').find('.check_number').removeAttr('required');
        $(e).closest('.row').find('.check_date').removeAttr('required');
    }
}


function getCtnQty(e){

    let product_id = $(e).closest('tr').find('.product_id').val();
    let Ctn=$(e).closest('tr').find('.ctn').val()?parseFloat($(e).closest('tr').find('.ctn').val()):0;
    let Pcs=$(e).closest('tr').find('.pcs').val()?parseFloat($(e).closest('tr').find('.pcs').val()):0;
    let returnCtn=$(e).closest('tr').find('.ctn_return').val()?parseFloat($(e).closest('tr').find('.ctn_return').val()):0;
    let returnPcs=$(e).closest('tr').find('.pcs_return').val()?parseFloat($(e).closest('tr').find('.pcs_return').val()):0;
    let ctnDamage=$(e).closest('tr').find('.ctn_damage').val()?parseFloat($(e).closest('tr').find('.ctn_damage').val()):0;
    let pcsDamage=$(e).closest('tr').find('.pcs_damage').val()?parseFloat($(e).closest('tr').find('.pcs_damage').val()):0;
    let pcsPrice=$(e).closest('tr').find('.per_pcs_price').val()?parseFloat($(e).closest('tr').find('.per_pcs_price').val()):0;

    let oldCtn=$(e).closest('tr').find('.old_ctn').val()?parseFloat($(e).closest('tr').find('.old_ctn').val()):0;
    let oldPcs=$(e).closest('tr').find('.old_pcs').val()?parseFloat($(e).closest('tr').find('.old_pcs').val()):0;
    let oldCtnDmg=$(e).closest('tr').find('.old_ctn_damage').val()?parseFloat($(e).closest('tr').find('.old_ctn_damage').val()):0;
    let oldPcsDmg=$(e).closest('tr').find('.old_pcs_damage').val()?parseFloat($(e).closest('tr').find('.old_pcs_damage').val()):0;
    let oldPcsPrice=$(e).closest('tr').find('.old_pcs_price').val()?parseFloat($(e).closest('tr').find('.old_pcs_price').val()):0;
    $.ajax({
        url: "{{route(currentUser().'.unit_data_get')}}",
        type: "GET",
        dataType: "json",
        data: { product_id:product_id },
        success: function(data) {
            //console.log(data);
            let oldTotalQty=(Ctn*data)+Pcs;
            let totalReturn=parseFloat(data*returnCtn)+returnPcs;
            let totalDamage=parseFloat(data*ctnDamage)+pcsDamage;
            let totalSalesQty=oldTotalQty-(totalReturn+totalDamage);
            let totalReceive=totalReturn+totalDamage;
            let subTotalPrice=(pcsPrice*oldTotalQty)-(pcsPrice*totalReceive);
            $(e).closest('tr').find('.subtotal_price').val(parseFloat(subTotalPrice).toFixed(2));
            $(e).closest('tr').find('.total_return_pcs').val(totalReturn);
            $(e).closest('tr').find('.total_damage_pcs').val(totalDamage);
            $(e).closest('tr').find('.total_sales_pcs').val(totalSalesQty);
            primarySubTotal();

            let oldSubPcs=(oldCtn*data)+oldPcs;
            let oldSubDmgPcs=(oldCtnDmg*data)+oldPcsDmg;
            let totalReturnQty=(oldSubPcs+oldSubDmgPcs);
            let oldSubtotalPrice=(totalReturnQty*oldPcsPrice);
            $(e).closest('tr').find('.actual_total_pcs').val(totalSalesQty);
            $(e).closest('tr').find('.old_actual_total_pcs').val(totalReturnQty);
            $(e).closest('tr').find('.old_total_return_pcs').val(oldSubPcs);
            $(e).closest('tr').find('.old_total_damage_pcs').val(oldSubDmgPcs);
            $(e).closest('tr').find('.return_subtotal_price').val(oldSubtotalPrice);
            return_total_calculate();
            FinalTotal();
        },
    });
}
function getCoinNote(e){
    let onetaka=$('.onetaka').val();
    let twotaka=$('.twotaka').val();
    let fivetaka=$('.fivetaka').val();
    let tentaka=$('.tentaka').val();
    let twentytaka=$('.twentytaka').val();
    let fiftytaka=$('.fiftytaka').val();
    let onehundredtaka=$('.onehundredtaka').val();
    let twohundredtaka=$('.twohundredtaka').val();
    let fivehundredtaka=$('.fivehundredtaka').val();
    let onethousandtaka=$('.onethousandtaka').val();
    let uponeTaka=onetaka*1;
    let uptwoTaka=twotaka*2;
    let upfiveTaka=fivetaka*5;
    let uptenTaka=tentaka*10;
    let uptwentyTaka=twentytaka*20;
    let upfiftyTaka=fiftytaka*50;
    let uponeHundredTaka=onehundredtaka*100;
    let uptwoHundredTaka=twohundredtaka*200;
    let upfiveHundredTaka=fivehundredtaka*500;
    let uponeThousanddTaka=onethousandtaka*1000;
    $('.onetakaCalculate').text(uponeTaka);
    $('.twotakaCalculate').text(uptwoTaka);
    $('.fivetakaCalculate').text(upfiveTaka);
    $('.tentakaCalculate').text(uptenTaka);
    $('.twentytakaCalculate').text(uptwentyTaka);
    $('.fiftytakaCalculate').text(upfiftyTaka);
    $('.onehundredtakaCalculate').text(uponeHundredTaka);
    $('.twohundredtakaCalculate').text(uptwoHundredTaka);
    $('.fivehundredtakaCalculate').text(upfiveHundredTaka);
    $('.onethousandtakaCalculate').text(uponeThousanddTaka);
    let allcoinNot=uponeTaka+uptwoTaka+upfiveTaka+uptenTaka+uptwentyTaka+upfiftyTaka+uponeHundredTaka+uptwoHundredTaka+upfiveHundredTaka+uponeThousanddTaka;
    console.log(allcoinNot);
    var finalTotalTaka=parseFloat($('.final_total_tk').val());
    $('.allConinUpdate').text(parseFloat(finalTotalTaka-allcoinNot).toFixed(2));
    $('.final_cash_extra').val(parseFloat(finalTotalTaka-allcoinNot).toFixed(2));
    $('.allcoinNot').text(parseFloat(allcoinNot).toFixed(2));
    $('.today_final_cash').val(parseFloat(allcoinNot).toFixed(2));
    if(parseFloat(finalTotalTaka-allcoinNot) <= 0 || finalTotalTaka <= 0){
        $('#savebutton').attr('disabled',false)
    }else{
        $('#savebutton').attr('disabled',true)
    }
    //$('.allConinUpdate').text(allcoinNot);

    //console.log(allcoinNot);
}


$(document).ready(function() {
    calculateTotalCtn();
    calculateTotalPcs();
    $('#sales_repeat').on('input','.ctn', function() {
        calculateTotalCtn();
    });
    $('#sales_repeat').on('input','.pcs', function() {
        calculateTotalPcs();
    });
});
function calculateTotalCtn() {
    let totalCtn = 0;
    $('.ctn').each(function() {
        const ctntotal = parseFloat($(this).val()) || 0;
        totalCtn += ctntotal;
    });
    $('#totalCtn').text(totalCtn);
}
function calculateTotalPcs() {
    let totalPcs = 0;
    $('.pcs').each(function() {
        const pcsTotal = parseFloat($(this).val()) || 0;
        totalPcs += pcsTotal;
    });
    $('#totalPcs').text(totalPcs);
}


        
    function shopSave() {
        let supId = $('#sup_id').val();
        let srId = $('#srUser_id').val();
        let shopName = $('#shop_name').val();
        let ownerName = $('#owner_name').val();
        let areaName = $('#area_id').val();
        let selectedAreaText = $("#area_id option:selected").text();
        let contactNo = $('#contact').val();
        let Address = $('#address').val();
        let Balance = $('#balance').val();
        let status = 0;
        // let category = $('#category-id').val();
        // Fetch CSRF token value from meta tag
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{route(currentUser().'.shop.store')}}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            data: {
                sup_id: supId,
                sr_id: srId,
                shop_name: shopName,
                owner_name: ownerName,
                area_name: areaName,
                contact: contactNo,
                address: Address,
                balance: Balance,
                status: status,
                // category: category,
                _token: csrfToken // Include CSRF token in data (optional)
            },
            success: function(response) {
                console.log(response);
                if (response.error) {
                    console.error('Error:', response.error);
                    return;
                }
                // Close the modal
                $('#shopName').modal('hide');
                $('#sup_id').val();
                $('#srUser_id').val();
                $('#shop_name').val('');
                $('#owner_name').val('');
                $('#area_name').val('');
                $('#contact').val('');
                $('#address').val('');
                $('#balance').val('');
                //alert(shopName +' - ' + selectedAreaText);
                //fetchUpdatedShops(shopName +' - ' + selectedAreaText);
                //populateAllShopSelectBoxes();
                fetchUpdatedShops(response.shop_id);
                
            },
            error: function(xhr, status, error) {
                console.error('Error saving option:', error);
                // Handle error scenario if needed
            }
        });
    }

    var selectedBox; // Variable to store the current box

    // Event listener for when the modal is shown
    $('#shopName').on('show.bs.modal', function (event) {
        var triggerElement = $(event.relatedTarget);
        selectedBox = triggerElement.data('box'); // Get the data-box value
    });
    
    function fetchUpdatedShops(shopName) {
        let supId = $('#sup_id').val();
        let areaName = $('#area_id').val();
        $.ajax({
            url: "{{ route('sales.getUpdatedShops') }}",
            type: 'GET',
            data: {
                sup_id: supId,
                area_name: areaName,
            },
            success: function(response) {
                if (response.shops) {
                    console.log(response);
                    let shopOptions = '<option value="">Select</option>';
                    response.shops.forEach(shop => {
                        shopOptions += `<option value="${shop.id}">${shop.shop_name} - ${shop.area ? shop.area.name : ''}</option>`;
                    });
                    
                    console.log(shopOptions);
                    //$('.old_due_shop_id').html(shopOptions);
                    // $('.shop_data').each(function() {
                    //   alert(shopName)
                    //      $(this).html(shopOptions).val(shopName).trigger('change.select2'); // Set the options 
                    // });
                    // Update the specific select element based on `selectedBox`
                    if (selectedBox === 1) {
                        $('.shop_main').each(function() {
                        $(this).html(shopOptions); // Set the new options list
                        $(this).val(""); // Clear any selected value, if necessary
                        $(this).trigger('change.select2'); // Reinitialize Select2 for styling (if using Select2)
                    });
                        $('.old_due_shop_id').html(shopOptions).val(shopName).trigger('change.select2');
                    } else if (selectedBox === 2) {
                        $('.shop_main').each(function() {
                        $(this).html(shopOptions); // Set the new options list
                        $(this).val(""); // Clear any selected value, if necessary
                        $(this).trigger('change.select2'); // Reinitialize Select2 for styling (if using Select2)
                    });
                        $('.new_due_shop_id').html(shopOptions).val(shopName).trigger('change.select2');
                    } else if (selectedBox === 3) {
                        $('.shop_main').each(function() {
                        $(this).html(shopOptions); // Set the new options list
                        $(this).val(""); // Clear any selected value, if necessary
                        $(this).trigger('change.select2'); // Reinitialize Select2 for styling (if using Select2)
                    });
                        $('.check_shop_id').html(shopOptions).val(shopName).trigger('change.select2');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching shops:', error);
            }
        });
    }
    
    function populateAllShopSelectBoxes() {
        let supId = $('#sup_id').val();
        let areaName = $('#area_id').val();
        $.ajax({
            url: "{{ route('sales.getUpdatedShops') }}",
            type: 'GET',
            data: {
                sup_id: supId,
                area_name: areaName,
            },
            success: function(response) {
                if (response.shops) {
                    // Generate options list
                    let shopOptions = '<option value="">Select</option>';
                    response.shops.forEach(shop => {
                        shopOptions += `<option value="${shop.id}">${shop.shop_name} - ${shop.area ? shop.area.name : ''}</option>`;
                    });
                    // Populate all select boxes with the class `.shop_data`
                    $('.shop_data').each(function() {
                        $(this).html(shopOptions); // Set the new options list
                        $(this).val(""); // Clear any selected value, if necessary
                        $(this).trigger('change.select2'); // Reinitialize Select2 for styling (if using Select2)
                    });
                    
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching shops:', error);
            }
        });
    }


</script>

<script>
    /* call on load page */
    $(document).ready(function(){
       $('.selecet_hide').hide();
   })
   let old_supplier_id=0;
   function srShow(value){
        let supplier = value;
         $('.selecet_hide').hide();
         $('.selecet_hide'+supplier).show();
         if(old_supplier_id!=supplier){
            $('#srUser_id').prop('selectedIndex', 0);
            $('#area_id').prop('selectedIndex', 0);
             old_supplier_id=supplier;
         }
    }

    //     // Event listener for when the modal is shown
    // $('#shopName').on('show.bs.modal', function (event) {
    //     // Get the element that triggered the modal
    //     var triggerElement = $(event.relatedTarget);
    //     $('#box').val(dataBoxValue);
    //     alert(triggerElement);
    //     // Retrieve the data-box value
    //     var dataBoxValue = triggerElement.data('box');

    //     // Inject the data-box value into the modal body
    //     $('#modalBoxContent').text("Data Box Value: " + dataBoxValue);
    // });
    
</script>
@endpush