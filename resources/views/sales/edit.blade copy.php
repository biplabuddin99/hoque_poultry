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
                        <form method="post" action="{{route(currentUser().'.sales.receive',encryptor('encrypt',$sales->id))}}">
                            @csrf
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
                                    <select class="form-select" name="shop_id">
                                        <option value="">Select</option>
                                        @forelse($shops as $sh)
                                        <option value="{{$sh->id}}" {{ $sales->shop_id==$sh->id?"selected":""}}> {{ $sh->shop_name}}-{{$sh->area_name}}</option>
                                        @empty
                                            <option value="">No data found</option>
                                        @endforelse
                                    </select>
                                </div>
                                @endif

                                @if (!empty($sales->dsr_id))
                                    <div class="col-lg-3 col-md-3 col-sm-6 mt-2 dsrNameContainer">
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
                                <div class="col-lg-3 col-md-3 col-sm-6 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input type="text" id="datepicker" class="form-control" value="{{ $sales->sales_date }}"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                            </div>
                            <!-- table bordered -->
                            <div class="row p-2 mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2">{{__('Product Name')}}</th>
                                                <th rowspan="2">{{__('CTN')}}</th>
                                                <th rowspan="2">{{__('PCS')}}</th>
                                                <th colspan="2">{{ __('Return') }}</th>
                                                <th colspan="2" class="text-danger">{{ __('Damage') }}</th>
                                                {{--  <th rowspan="2">{{__('TP/Tp Free')}}</th>  --}}
                                                <th rowspan="2">{{__('CTN(Price)')}}</th>
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
                                            @if ($sales->temporary_sales_details)
                                                @foreach ($sales->temporary_sales_details as $salesdetails)
                                                    <tr>
                                                        <td>
                                                            <select class="choices form-select product_id" id="product_id" onchange="doData(this);" name="product_id[]">
                                                                <option value="">Select Product</option>
                                                                @forelse (\App\Models\Product\Product::where(company())->get() as $pro)
                                                                <option data-dp='{{ $pro->dp_price }}' value="{{ $pro->id }}" {{ old('product_id', $pro->id)==$salesdetails->product_id ? "selected":""}}>{{ $pro->product_name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </td>
                                                        <td><input readonly class="form-control ctn" type="text" name="ctn[]" value="{{ old('ctn',$salesdetails->ctn) }}" placeholder="ctn"></td>
                                                        <td><input readonly class="form-control pcs" type="text" name="pcs[]"value="{{ old('pcs',$salesdetails->pcs) }}" placeholder="pcs"></td>
                                                        <td><input class="form-control ctn_return" type="text" name="ctn_return[]" value="" placeholder="ctn return"></td>
                                                        <td><input class="form-control pcs_return" type="text" name="pcs_return[]"value="" placeholder="pcs return"></td>
                                                        <td><input class="form-control ctn_damage" type="text" name="ctn_damage[]" value="" placeholder="ctn damage"></td>
                                                        <td><input class="form-control pcs_damage" type="text" name="pcs_damage[]"value="" placeholder="pcs damage"></td>
                                                        {{--  <td style="width: 110px;">
                                                            <select class="form-select" name="select_tp_tpfree">
                                                                <option value="">Select</option>
                                                                <option value="1" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="1" ? "selected":""}}>TP</option>
                                                                <option value="2" {{ old('select_tp_tpfree', $salesdetails->select_tp_tpfree)=="2" ? "selected":""}}>TP Free</option>
                                                            </select>
                                                        </td>  --}}
                                                        <td><input class="form-control" type="text" name="ctn_price[]" value="{{ old('ctn_price',$salesdetails->ctn_price) }}" placeholder="Ctn Price"></td>
                                                        <td><input class="form-control" type="text" name="subtotal_price[]" value="{{ old('subtotal_price',$salesdetails->subtotal_price) }}" placeholder="Sub total"></td>
                                                        <td></td>
                                                    </tr>

                                                @endforeach
                                            @endif
                                            <tr>
                                                <td class="text-end" colspan="8"><h5 for="totaltk">{{__('Total Taka')}}</h5></td>
                                                <td class="text-end" colspan="9">
                                                    <input type="text" class="form-control" value="{{ $sales->total }}" name="total_taka">
                                                    <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot id="tfootSection" style="display: none;">
                                            <tr>
                                                <td class="text-end" colspan="8"><h5 for="return_total">{{__('Return Total Taka')}}</h5></td>
                                                <td class="text-end" colspan="9">
                                                    <input readonly type="text" class="form-control return_total_taka" value="" name="return_total_taka">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
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
                                            <select class="form-select old_due_shop_id" name="old_due_shop_id[]">
                                                <option value="">Select</option>
                                                @foreach (\App\Models\Settings\Shop::all() as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('old_due_tk')}}" name="old_due_tk[]" placeholder="Tk">
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
                                            <select class="form-select new_due_shop_id" name="new_due_shop_id[]">
                                                <option value="">Select</option>
                                                @foreach (\App\Models\Settings\Shop::all() as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('new_due_tk')}}" name="new_due_tk[]" placeholder="Tk">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                 <span onClick='newDue();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row new_receive">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('New Receive')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="form-select new_receive_shop_id" name="new_receive_shop_id[]">
                                                <option value="">Select</option>
                                                @foreach (\App\Models\Settings\Shop::all() as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('new_receive_tk')}}" name="new_receive_tk[]" placeholder="Tk">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group text-primary" style="font-size:1.5rem">
                                                 <span onClick='newReceive();'><i class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row check_no">
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <h5 for="check">{{__('Check')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
                                            <select class="form-select check_shop_id" name="check_shop_id[]">
                                                <option value="">Select</option>
                                                @foreach (\App\Models\Settings\Shop::all() as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('check_shop_tk')}}" name="check_shop_tk[]" placeholder="Tk">
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
                                                <input type="text" class="form-control" value="{{ old('expenses')}}" name="expenses">
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
                                                <input type="text" class="form-control" value="{{ old('commission')}}" name="commission">
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
                                                <input type="text" class="form-control" value="{{ old('final_total')}}" name="final_total">
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
    function addRow(){
        var tfootSection = document.getElementById('tfootSection');
        tfootSection.style.display = 'table-row-group';
        var row=`
            <tr>
                <td colspan="3">
                    <select class="choices form-select product_id" id="product_id" onchange="doData(this);" name="product_id[]">
                        <option value="">Select Product</option>
                        @forelse (\App\Models\Product\Product::where(company())->get() as $pro)
                        <option data-dp='{{ $pro->dp_price }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td><input class="form-control ctn" type="text" name="return_ctn[]" value="" placeholder="ctn return"></td>
                <td><input class="form-control pcs" type="text" name="return_pcs[]"value="" placeholder="pcs return"></td>
                <td><input class="form-control ctn" type="text" name="damage_ctn[]" value="" placeholder="ctn damage"></td>
                <td><input class="form-control pcs" type="text" name="damage_pcs[]"value="" placeholder="pcs damage"></td>
                {{--  <td>
                    <select class="form-select" name="select_tp_tpfree">
                        <option value="">Select</option>
                        <option value="1">TP</option>
                        <option value="2">TP Free</option>
                    </select>
                </td>  --}}
                <td><input class="form-control" type="text" name="ctn_price[]" value="" placeholder="Ctn Price"></td>
                <td><input class="form-control return_subtotal_price" type="text" onkeyup="return_total_calculate();" name="return_subtotal_price[]" value="" placeholder="Sub total"></td>
                <td>
                    <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                    <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>
                </td>
            </tr>`;
            $('#sales_repeat').append(row);
        }

function removeRow(e){
    if (confirm("Are you sure you want to remove this row?")) {
    $(e).closest('tr').remove();
    }
}
function return_total_calculate() {
    var subtotal = 0;
    $('.return_subtotal_price').each(function() {
        subtotal += parseFloat($(this).val());
    });
    // $('.total').text(parseFloat(subtotal).toFixed(2));
    $('.return_total_taka').val(parseFloat(subtotal).toFixed(2));

}

function oldDue(){
    var oldDue=`
    <div class="row append_remove">
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <h5 for="check">{{__('Old Due')}}</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
            <select class="form-select shop_name" name="shop_name">
                <option value="">Select</option>
                @foreach (\App\Models\Settings\Shop::all() as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control" value="{{ old('total_tk')}}" name="total_tk" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeOld(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                 <span onClick='oldDue();'><i class="bi bi-plus-square-fill"></i></span>
            </div>
        </div>
    </div>
`;

    $('.olddue').append(oldDue);
}
function removeOld(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
    }
}
function newDue(){
    var newDue=`
    <div class="row appendnew_remove">
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group">
                <h5 for="check">{{__('New Due')}}</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
            <select class="form-select shop_name" name="shop_name">
                <option value="">Select</option>
                @foreach (\App\Models\Settings\Shop::all() as $shop)
                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control" value="{{ old('total_tk')}}" name="total_tk" placeholder="Tk">
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="form-group text-primary" style="font-size:1.5rem">
                <span onClick='removeNewDue(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                 <span onClick='newDue();'><i class="bi bi-plus-square-fill"></i></span>
            </div>
        </div>
    </div>
`;

    $('.newdue').append(newDue);
}
function removeNewDue(e){
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('.row').remove();
    }
}

function newReceive(){
    var newReceive=`
    <div class="col-lg-2 col-md-3 col-sm-6">
        <div class="form-group">
            <h5 for="check">{{__('New Receive')}}</h5>
        </div>
    </div>
    <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
        <select class="form-select shop_name" name="shop_name">
            <option value="">Select</option>
            @foreach (\App\Models\Settings\Shop::all() as $shop)
            <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="form-group">
            <input type="text" class="form-control" value="{{ old('total_tk')}}" name="total_tk" placeholder="Tk">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6">
        <div class="form-group text-primary" style="font-size:1.5rem">
             <span onClick='newReceive();'><i class="bi bi-plus-square-fill"></i></span>
        </div>
    </div>`;

    $('.new_receive').append(newReceive);
}
function newCheck(){
    var newCheck=`
    <div class="col-lg-2 col-md-3 col-sm-6">
        <div class="form-group">
            <h5 for="check">{{__('Check')}}</h5>
        </div>
    </div>
    <div class="col-lg-4 col-md-3 col-sm-6 shopNameContainer">
        <select class="form-select shop_name" name="shop_name">
            <option value="">Select</option>
            @foreach (\App\Models\Settings\Shop::all() as $shop)
            <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="form-group">
            <input type="text" class="form-control" value="{{ old('total_tk')}}" name="total_tk" placeholder="Tk">
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6">
        <div class="form-group">
            <input type="date" class="form-control" value="{{ old('date')}}" name="total_tk" placeholder="Date">
        </div>
    </div>
    <div class="col-lg-1 col-md-3 col-sm-6">
        <div class="form-group text-primary" style="font-size:1.5rem">
             <span onClick='newReceive();'><i class="bi bi-plus-square-fill"></i></span>
        </div>
    </div>`;

    $('.check_no').append(newCheck);
}
</script>
@endpush
