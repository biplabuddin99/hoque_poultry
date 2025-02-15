@extends('layout.app')

@section('pageTitle',trans('Sales Closing'))
@section('pageSubTitle',trans('Return'))

@section('content')
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
                                {{--  <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>Shop/Dsr</b></label>
                                    <select class="form-select shop_dsr" onclick="getShopDsr()" name="select_shop_dsr">
                                        <option value="">Select</option>
                                        <option value="shop">Shop</option>
                                        <option value="dsr">DSR</option>
                                    </select>
                                </div>  --}}
                                @if (!empty($sales->shop_id))
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2 shopNameContainer">
                                    <label for=""><b>Shop Name</b></label>
                                    <input readonly class="form-control" type="text" value="{{ $sales->shop?->shop_name }}" placeholder="">
                                    <input class="form-control" type="hidden" name="shop_id" value="{{ old('shop_id',$sales->shop_id) }}" placeholder="">
                                    {{--  <select class="form-select" name="shop_id">
                                        <option value="">Select</option>
                                        @forelse($shops as $sh)
                                        <option value="{{$sh->id}}" {{ $sales->shop_id==$sh->id?"selected":""}}> {{ $sh->shop_name}}</option>
                                        @empty
                                            <option value="">No data found</option>
                                        @endforelse
                                    </select>  --}}
                                </div>
                                @endif

                                @if (!empty($sales->dsr_id))
                                    <div class="col-lg-3 col-md-3 col-sm-6 mt-2 dsrNameContainer">
                                        <label for=""><b>DSR Name</b></label>
                                        <input readonly class="form-control" type="text" value="{{ $sales->dsr?->name }}" placeholder="">
                                        <input class="form-control" type="hidden" name="dsr_id" value="{{ old('dsr_id',$sales->dsr_id) }}" placeholder="">
                                        {{--  <select class="form-select" name="dsr_id">
                                            <option value="">Select</option>
                                            @forelse($dsr as $d)
                                            <option value="{{$d->id}}" {{ $sales->dsr_id==$d->id?"selected":""}}> {{ $d->name}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>  --}}
                                    </div>
                                @endif
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input readonly type="text" id="datepicker" class="form-control" value="{{ $sales?->sales_date }}"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>SR Name</b></label>
                                    <input readonly type="text" id="" class="form-control" value="{{ $sales->sr?->name }}">
                                    <input class="form-control" type="hidden"  name="sr_id" value="{{ $sales->sr_id }}" placeholder="">
                                </div>
                                @if (!empty($sales->distributor_id))
                                    <div class="col-lg-3 col-md-3 col-sm-6 mt-2 dsrNameContainer">
                                        <label for=""><b>Distributor Name</b></label>
                                        <input readonly class="form-control" type="text" value="{{ $sales->distributor?->name }}" placeholder="">
                                        <input class="form-control" type="hidden" name="distributor_id" value="{{ old('distributor_id',$sales->distributor_id) }}" placeholder="">
                                    </div>
                                @endif
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
                                                <th rowspan="2">{{__('PCS(Price)')}}</th>
                                                {{--  <th rowspan="2">{{__('CTN(Price)')}}</th>  --}}
                                                <th rowspan="2">{{__('Sub-Total(Price)')}}</th>
                                                <th rowspan="2"></th>
                                            </tr>
                                            <tr>
                                                <th>CTN</th>
                                                <th>PCS</th>
                                                <th class="text-danger">CTN</th>
                                                <th class="text-danger">PCS</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sales_repeat">
                                            @if ($sales?->temporary_sales_details)
                                                @foreach ($sales?->temporary_sales_details as $salesdetails)
                                                    <tr>
                                                        <td>
                                                            <input readonly class="form-control" type="text" value="{{ $salesdetails->product?->product_name }}">
                                                            <input readonly class="form-control product_id" type="hidden" name="product_id[]" value="{{ $salesdetails->product_id }}">
                                                            {{--  <select class="choices form-select product_id" id="product_id" onchange="doData(this);" name="product_id[]">
                                                                <option value="">Select Product</option>
                                                                @forelse (\App\Models\Product\Product::where(company())->get(); as $pro)
                                                                <option data-dp='{{ $pro->dp_price }}' value="{{ $pro->id }}" {{ old('product_id', $pro->id)==$salesdetails->product_id ? "selected":""}}>{{ $pro->product_name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>  --}}
                                                        </td>
                                                        <td><input readonly class="form-control ctn" type="text" name="ctn[]" value="{{ old('ctn',$salesdetails->ctn) }}" placeholder="ctn"></td>
                                                        <td><input readonly class="form-control pcs" type="text" name="pcs[]"value="{{ old('pcs',$salesdetails->pcs) }}" placeholder="pcs"></td>
                                                        <td><input class="form-control ctn_return" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="ctn_return[]" value="" placeholder="ctn return"></td>
                                                        <td><input class="form-control pcs_return" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="pcs_return[]"value="" placeholder="pcs return"></td>
                                                        <td><input class="form-control ctn_damage" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="ctn_damage[]" value="" placeholder="ctn damage"></td>
                                                        <td><input class="form-control pcs_damage" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this);" onchange="getCtnQty(this);" name="pcs_damage[]"value="" placeholder="pcs damage"></td>
                                                        {{--  <td style="width: 110px;">
                                                            <select class="form-select" name="select_tp_tpfree">
                                                                <option value="">Select</option>
                                                                <option value="1" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="1" ? "selected":""}}>TP</option>
                                                                <option value="2" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="2" ? "selected":""}}>TP Free</option>
                                                            </select>
                                                        </td>  --}}
                                                        <td>
                                                            <input readonly class="form-control per_pcs_price" type="text" name="pcs_price[]" value="{{ old('pcs_price',$salesdetails->pcs_price) }}" placeholder="PCS Price">
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
                                                        <td><input readonly class="form-control subtotal_price" type="text" name="subtotal_price[]" value="{{ old('subtotal_price',$salesdetails->subtotal_price) }}" placeholder="Sub total"></td>
                                                        <td></td>
                                                    </tr>

                                                @endforeach
                                            @endif
                                            <tr>
                                                <td class="text-end" colspan="8"><h5 for="totaltk">{{__('Total Taka')}}</h5></td>
                                                <td class="text-end" colspan="9">
                                                    <input type="text" class="form-control ptotal_taka" value="{{ $sales->total }}" name="daily_total_taka">
                                                    <span onClick='previousProduct();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot id="tfootSection" style="display: none;">
                                            <tr>
                                                <td class="text-end" colspan="8"><h5 for="return_total">{{__('Return Total Taka')}}</h5></td>
                                                <td class="text-end" colspan="9">
                                                    <input readonly type="text" class="form-control return_total_taka" value="0" name="return_total_taka">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-center border-end">
                                   <b>Note and Coin</b>
                                  <table class="ms-3" width="auto" cellspcing="0">
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>1</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control onetaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="onetakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>2</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control twotaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="twotakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>5</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control fivetaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="fivetakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>10</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control tentaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="tentakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>20</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control twentytaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="twentytakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>50</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control fiftytaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="fiftytakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>100</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control onehundredtaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="onehundredtakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>200</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control twohundredtaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="twohundredtakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>500</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control fivehundredtaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="fivehundredtakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="bg-info text-white px-3 text-center"><b>1000</b></td>
                                      <td><input onkeyup="getCoinNote(this)" onblur="getCoinNote(this);" onchange="getCoinNote(this);" class="form-control onethousandtaka" type="number" /></td>
                                      <th class="ps-1"> = </th>
                                      <th class="onethousandtakaCalculate">0</th>
                                    </tr>
                                    <tr>
                                      <td class="text-white px-3 text-center"></td>
                                      <th>Total</th>
                                      <th class="ps-1"> =
                                        <input type="hidden" class="today_final_cash" value="" name="today_final_cash">
                                      </th>
                                      <th class="allConinUpdate">0</th>
                                    </tr>
                                    <tr>
                                      <th colspan="2">Final Total</th>
                                      <th class="ps-1"> = </th>
                                      <th class=""><input disabled type="text" class="form-control final_total_tk" value="{{ old('final_total')}}"></th>
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
                                  <div class="row olddue">
                                      <div class="col-lg-2 col-md-3 col-sm-6">
                                          <div class="form-group">
                                              <h5 for="check">{{__('Old Due')}}</h5>
                                          </div>
                                      </div>
                                      <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                          <select class="select2 form-select old_due_shop_id" name="old_due_shop_id[]">
                                              <option value="">Select</option>
                                              @foreach ($shops as $shop)
                                              <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                      <div class="col-lg-3 col-md-3 col-sm-6">
                                          <div class="form-group">
                                              <input type="text" class="form-control old_due_tk" onkeyup="totalOldDue();FinalTotal();" onblur="totalOldDue();FinalTotal();" onchange="totalOldDue();FinalTotal();" value="{{ old('old_due_tk')}}" name="old_due_tk[]" placeholder="Tk">
                                              <input type="hidden" class="form-control o_due_tk" value="0">
                                          </div>
                                      </div>
                                      <div class="col-lg-2 col-md-3 col-sm-6">
                                          <div class="form-group text-primary" style="font-size:1.5rem">
                                               <span onClick='oldDue();'><i class="bi bi-plus-square-fill"></i></span>
                                          </div>
                                      </div>
                                  </div>
                                  <hr>
                                  <div class="row newdue">
                                      <div class="col-lg-2 col-md-3 col-sm-6">
                                          <div class="form-group">
                                              <h5 for="check">{{__('new Due')}}</h5>
                                          </div>
                                      </div>
                                      <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                          <select class="select2 form-select new_due_shop_id" name="new_due_shop_id[]">
                                              <option value="">Select</option>
                                              @foreach ($shops as $shop)
                                              <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                              @endforeach
                                          </select>
                                      </div>

                                      <div class="col-lg-3 col-md-3 col-sm-6">
                                          <div class="form-group">
                                              <input type="text" class="form-control new_due_tk" onkeyup="totalNewDue();FinalTotal();" onblur="totalNewDue();FinalTotal();" onchange="totalNewDue();FinalTotal();" value="{{ old('new_due_tk')}}" name="new_due_tk[]" placeholder="Tk">
                                              <input type="hidden" class="form-control n_due_tk" value="0">
                                          </div>
                                      </div>
                                      <div class="col-lg-2 col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="{{ old('new_due_date')}}" name="new_due_date[]" placeholder="Date">
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
                                      <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                          <select class="select2 form-select check_shop_id" name="check_shop_id[]">
                                              <option value="">Select</option>
                                              @foreach ($shops as $shop)
                                              <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                      <div class="col-lg-3 col-md-3 col-sm-6">
                                          <div class="form-group">
                                              <input type="text" class="form-control check_shop_tk" onkeyup="totalNewCheck();FinalTotal();" onblur="totalNewCheck();FinalTotal();" onchange="totalNewCheck();FinalTotal();" value="{{ old('check_shop_tk')}}" name="check_shop_tk[]" placeholder="Tk">
                                              <input type="hidden" class="form-control c_shop_tk" value="0">
                                          </div>
                                      </div>
                                      <div class="col-lg-2 col-md-3 col-sm-6">
                                          <div class="form-group">
                                              <input type="date" class="form-control" value="{{ old('check_date')}}" name="check_date[]" placeholder="Date">
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
                                              <input type="text" class="form-control cash" onkeyup="FinalTotal()" onblur="FinalTotal()" onchange="FinalTotal()" value="{{ old('cash')}}" name="cash">
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
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push("scripts")
<script>
    FinalTotal();
    function previousProduct(){
        var tfootSection = document.getElementById('tfootSection');
        tfootSection.style.display = 'table-row-group';
        var previousProduct=`
            <tr>
                <td colspan="3">
                    <select class="form-select product_id" id="product_id" onchange="getCtnQty(this);" name="return_product_id[]">
                        <option value="">Select Product</option>
                        @forelse (\App\Models\Product\Product::where(company())->get(); as $pro)
                        <option data-dp='{{ $pro->dp_price }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td><input class="form-control old_ctn" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_ctn_return[]" value="" placeholder="ctn return"></td>
                <td><input class="form-control old_pcs" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_pcs_return[]" value="" placeholder="pcs return"></td>
                <td><input class="form-control old_ctn_damage" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_ctn_damage[]" value="" placeholder="ctn damage"></td>
                <td><input class="form-control old_pcs_damage" type="text" onkeyup="getCtnQty(this)" onblur="getCtnQty(this)" onchange="getCtnQty(this)" name="old_pcs_damage[]" value="" placeholder="pcs damage"></td>
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
                    {{--  <span onClick='previousProduct();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>  --}}
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
    $('.final_total_tk').val(parseFloat(total).toFixed(2));
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
            <select class="select2 form-select old_due_shop_id" name="old_due_shop_id[]">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control old_due_tk" onkeyup="totalOldDue();FinalTotal();" onblur="totalOldDue();FinalTotal();" onchange="totalOldDue();FinalTotal();" value="{{ old('old_due_tk')}}" name="old_due_tk[]" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeOld(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                 {{--  <span onClick='oldDue();'><i class="bi bi-plus-square-fill"></i></span>  --}}
            </div>
        </div>
    </div>
    `;
    $('.olddue').append(oldDue);
    $('.select2').select2();
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
            <select class="select2 form-select new_due_shop_id" name="new_due_shop_id[]">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
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
                <input type="date" class="form-control" value="{{ old('new_due_date')}}" name="new_due_date[]" placeholder="Date">
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
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
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
        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
            <select class="select2 form-select check_shop_id" name="check_shop_id[]">
                <option value="">Select</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control check_shop_tk" onkeyup="totalNewCheck();FinalTotal();" onblur="totalNewCheck();FinalTotal();" onchange="totalNewCheck();FinalTotal();" value="{{ old('check_shop_tk')}}" name="check_shop_tk[]" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="date" class="form-control" value="{{ old('check_date')}}" name="check_date[]" placeholder="Date">
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
}
function removeNewCheck(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
        totalNewCheck();
        FinalTotal();
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
    $('.today_final_cash').val(allcoinNot);
    //$('.allConinUpdate').text(allcoinNot);

    //console.log(allcoinNot);
}
</script>
@endpush
