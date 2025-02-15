@extends('layout.app')

@section('pageTitle',trans('Sales Update'))
@section('pageSubTitle',trans('Update'))

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
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" action="{{route(currentUser().'.sales.primaryStore',encryptor('encrypt',$sales->id))}}" onsubmit="return confirm('Are you Sure to Update?')">
                            @csrf
                            @method('POST')
                            <div class="row p-2 mt-4">
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Shop/Dsr</b></label>
                                    <select class="form-select" onclick="getShopDsr()" name="select_shop_dsr">
                                        <option value="">Select</option>
                                        <option value="shop" {{ $sales->select_shop_dsr ==='shop'?"selected":"" }}>Shop</option>
                                        <option value="dsr" {{ $sales->select_shop_dsr ==='dsr'?"selected":"" }}>DSR</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2" id="shopNameContainer"  @if($sales->shop_id) style="display: block;" @else style="display: none;" @endif>
                                    <label for=""><b>Shop Name</b></label>
                                    <select class="form-select" name="shop_id">
                                        <option value="">Select</option>
                                        @foreach (\App\Models\Settings\Shop::where('sup_id',$sales->distributor_id)->get() as $sh)
                                        <option value="{{ $sh->id }}" {{ $sales->shop_id==$sh->id?'selected':'' }}>{{ $sh->shop_name }}-{{$sh->area_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2" id="dsrNameContainer" @if($sales->dsr_id) style="display: block;" @else style="display: none;" @endif>
                                    <label for=""><b>DSR Name</b></label>
                                    <select class="form-select" name="dsr_id">
                                        <option value="">Select</option>
                                        @foreach (\App\Models\User::where('distributor_id',$sales->distributor_id)->where('role_id',4)->get() as $d)
                                        <option value="{{ $d->id }}" {{ $sales->dsr_id==$d->id?'selected':'' }}>{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input type="text" id="datepicker" class="form-control" value="{{ $sales->sales_date }}"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>SR</b></label>
                                    <select name="sr_id" class="choices form-select">
                                        <option value="">Select</option>
                                        @forelse (\App\Models\User::where('distributor_id',$sales->distributor_id)->where('role_id',5)->get() as $p)
                                            <option value="{{$p->id}}" {{ $sales->sr_id==$p->id?"selected":""}}>{{$p->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mt-2">
                                    {{-- <label for="cat">{{__('Distributor')}}<span class="text-danger">*</span></label>
                                    <select class="form-select supplier_id" name="distributor_id">
                                        <option value="">Select Distributor</option>
                                        @forelse (App\Models\Settings\Supplier::where(company())->get() as $sup)
                                            <option value="{{ $sup->id }}" {{ $sales->distributor_id==$sup->id?"selected":""}}>{{ $sup->name }}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select> --}}
                                    <label for="cat">{{__('Distributor')}}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{$sales->distributor?->name}}" readonly>
                                    <input type="hidden" name="distributor_id" value="{{$sales->distributor_id}}">
                                </div>
                            </div>
                            <!-- table bordered -->
                            <div class="row p-2 mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col" width="30%">{{__('Product Name')}}</th>
                                                <th scope="col">{{__('CTN')}}</th>
                                                <th scope="col">{{__('PCS')}}</th>
                                                <th scope="col">{{__('Tp/Tpfree')}}</th>
                                                <th scope="col">{{__('CTN Price')}}</th>
                                                <th scope="col">{{__('PCS Price')}}</th>
                                                <th scope="col">{{__('Sub-Total')}}</th>
                                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sales_repeat">
                                            @if ($sales->temporary_sales_details)
                                                @foreach ($sales->temporary_sales_details as $salesdetails)
                                                    <tr>
                                                        <td>
                                                            <select class="choices form-select product_id" id="product_id" name="product_id[]" onchange="changeProduct(this)">
                                                                <option value="0">Select Product</option>
                                                                @forelse (\App\Models\Product\Product::where('distributor_id',$sales->distributor_id)->where(company())->get() as $pro)
                                                                <option  data-tp='{{ $pro->tp_price }}' data-tp_free='{{ $pro->tp_free }}' value="{{ $pro->id }}" {{ $salesdetails->product_id==$pro->id?'selected':'' }}>{{ $pro->product_name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </td>
                                                        <td><input class="form-control ctn" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="ctn[]" value="{{ $salesdetails->ctn }}" placeholder="ctn"></td>
                                                        <td><input class="form-control pcs" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="pcs[]"value="{{ $salesdetails->pcs }}" placeholder="pcs"></td>
                                                        <td>
                                                            <select class="form-select select_tp_tpfree" name="select_tp_tpfree[]" onchange="productData(this);">
                                                                <option value="1" {{ $salesdetails->select_tp_tpfree ==1?"selected":"" }}>TP</option>
                                                                <option value="2" {{ $salesdetails->select_tp_tpfree ==2?"selected":"" }}>TP Free</option>
                                                            </select>
                                                        </td>
                                                        <td><input class="form-control ctn_price" type="text" name="ctn_price[]" value="{{ $salesdetails->ctn_price }}" placeholder="Tp Price"></td>
                                                        <td><input readonly class="form-control per_pcs_price" name="per_pcs_price[]" type="text" value="{{ $salesdetails->pcs_price }}" placeholder="PCS Price"></td>
                                                        <td>
                                                            <input class="form-control subtotal_price" type="text" name="subtotal_price[]" value="{{ $salesdetails->subtotal_price }}" placeholder="Sub-Total">
                                                            <input class="form-control totalquantity_pcs" type="hidden" name="totalquantity_pcs[]" value="{{ $salesdetails->totalquantity_pcs }}">
                                                        </td>
                                                        <td>
                                                            <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                                                            <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-5 mt-2 pe-2 text-end">
                                            <label for="" class="form-group"><h4>Total</h4></label>
                                        </div>
                                        <div class="col-lg-2 mt-2 text-end">
                                            <label for="" class="form-group"><h5 class="total">{{ $sales->total }} TK</h5></label>
                                            <input type="hidden" name="total" value="{{ $sales->total }}" class="form-control total_p">
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end my-2">
                                <button type="submit" class="btn btn-info">Update</button>
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

var row=`
    <tr>
        <td>
            <select class="choices form-select product_id" id="product_id" name="product_id[]" onchange="changeProduct(this);">
                <option value="0">Select Product</option>
                @forelse (\App\Models\Product\Product::where('distributor_id',$sales->distributor_id)->where(company())->get() as $pro)
                <option  data-tp='{{ $pro->tp_price }}' data-tp_free='{{ $pro->tp_free }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                @empty
                @endforelse
            </select>
        </td>
        <td><input class="form-control ctn" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="ctn[]" value="" placeholder="ctn"></td>
        <td><input class="form-control pcs" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="pcs[]"value="" placeholder="pcs"></td>
        <td>
            <select class="form-select select_tp_tpfree" name="select_tp_tpfree[]" onchange="productData(this);">
                <option value="1">TP</option>
                <option value="2">TP Free</option>
            </select>
        </td>
        <td><input class="form-control ctn_price" type="text" name="ctn_price[]" value="" placeholder="Tp Price"></td>
        <td><input readonly class="form-control per_pcs_price" name="per_pcs_price[]" type="text" value="" placeholder="PCS Price"></td>
        <td>
            <input class="form-control subtotal_price" type="text" name="subtotal_price[]" value="" placeholder="Sub-Total">
            <input class="form-control totalquantity_pcs" type="hidden" name="totalquantity_pcs[]" value="">
        </td>
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
        total_calculate();
    }
}

function changeProduct(e){
    var productId = parseInt($(e).closest('tr').find('.product_id option:selected').val());
    console.log(productId);
    if(productId == 0){
        $(e).closest('tr').find('.ctn').val('');
        $(e).closest('tr').find('.pcs').val('');
        $(e).closest('tr').find('.ctn_price').val('');
        $(e).closest('tr').find('.per_pcs_price').val('');
        $(e).closest('tr').find('.subtotal_price').val('');
    }else{
        productData(e);
    }
}

function productData(e) {
    var selectedOption = parseInt($(e).closest('tr').find('.select_tp_tpfree').val());
    //console.log(selectedOption)
    var tp = $(e).closest('tr').find('.product_id option:selected').attr('data-tp');
    var tpFree = $(e).closest('tr').find('.product_id option:selected').attr('data-tp_free');
    var productId = parseInt($(e).closest('tr').find('.product_id option:selected').val());
    var ctn = $(e).closest('tr').find('.ctn').val() ? parseFloat($(e).closest('tr').find('.ctn').val()) : 0;
    var pcs = $(e).closest('tr').find('.pcs').val() ? parseFloat($(e).closest('tr').find('.pcs').val()) : 0;
    $.ajax({
        url: "{{route(currentUser().'.sales_unit_data_get')}}",
        type: "GET",
        dataType: "json",
        data: { product_id: productId },
        success: function (data) {
            // this function have doController UnitDataGet return qty
            //console.log(data)
            let totalqty=((data.unit*ctn)+pcs);
            //console.log(totalqty)
            $(e).closest('tr').find('.totalquantity_pcs').val(totalqty);
            if(data.unit){
                //let pcstp=parseFloat(tp / data).toFixed(2);
                let ctnTp=parseFloat(tp * data.unit).toFixed(2);
                //let pcstpFree=parseFloat(tpFree / data).toFixed(2);
                let ctntpFree=parseFloat(tpFree * data.unit).toFixed(2);
                let tpCtnPrice = parseFloat(ctnTp * ctn);
                let tpPcsPrice = parseFloat(tp * pcs);
                let tpFreeCtnPrice = parseFloat((tpFree * data.unit) * ctn);
                let tpFreePcsPrice = parseFloat(tpFree * pcs);
                var TpSubtotal = parseFloat(tpCtnPrice + tpPcsPrice).toFixed(2);
                var TpFreeSubtotal = parseFloat(tpFreeCtnPrice+ tpFreePcsPrice).toFixed(2);
                //let tpPcsPrice = parseFloat((tp / data) * pcs);
                //let tpFreePcsPrice = parseFloat((tpFree / data) * pcs);
                //var TpSubtotal = parseFloat((ctn * tp) + tpPcsPrice).toFixed(2);
                //var TpFreeSubtotal = parseFloat((ctn * tpFree) + tpFreePcsPrice).toFixed(2);

                if (selectedOption === 1) {
                    $(e).closest('tr').find('.ctn_price').val(ctnTp);
                    $(e).closest('tr').find('.per_pcs_price').val(tp);
                    $(e).closest('tr').find('.subtotal_price').val(TpSubtotal);
                } else if (selectedOption === 2) {
                    $(e).closest('tr').find('.ctn_price').val(ctntpFree);
                    $(e).closest('tr').find('.per_pcs_price').val(tpFree);
                    $(e).closest('tr').find('.subtotal_price').val(TpFreeSubtotal);
                } else {
                    $(e).closest('tr').find('.ctn_price').val("");
                    $(e).closest('tr').find('.subtotal_price').val("");
                }
                total_calculate();
            }
        },
        error: function () {
            console.error("Error fetching data from the server.");
        },
    });

}


function total_calculate() {
    var subtotal = 0;
    $('.subtotal_price').each(function() {
        subtotal += parseFloat($(this).val());
    });
    $('.total').text(parseFloat(subtotal).toFixed(2));
    $('.total_p').val(parseFloat(subtotal).toFixed(2));

}


</script>

<script>
    function getShopDsr() {
        var selectedOption = document.querySelector('select[name="select_shop_dsr"]').value;

        var shopNameContainer = document.getElementById("shopNameContainer");
        var dsrNameContainer = document.getElementById("dsrNameContainer");

        if (selectedOption === "shop") {
            shopNameContainer.style.display = "block";
            dsrNameContainer.style.display = "none";
            getShopData();
        } else if (selectedOption === "dsr") {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "block";
            getDsrData();
        } else {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "none";
        }
    }
    // function getShopData() {
    //     $.ajax({
    //         url: "{{ route(currentUser().'.get_shop') }}",
    //         type: "GET",
    //         dataType: "json",
    //         success: function(data) {
    //             populateShopOptions(data);
    //         },
    //         error: function(xhr, status, error) {
    //             console.log("Error: " + error);
    //         }
    //     });
    // }

    // function getDsrData() {
    //     $.ajax({
    //         url: "{{ route(currentUser().'.get_dsr') }}",
    //         type: "GET",
    //         dataType: "json",
    //         success: function(data) {
    //             populateDsrOptions(data);
    //         },
    //         error: function(xhr, status, error) {
    //             console.log("Error: " + error);
    //         }
    //     });
    // }

    // function populateShopOptions(data) {
    //     var selectElement = document.querySelector('select[name="shop_id"]');
    //     selectElement.innerHTML = "";

    //     data.forEach(function(item) {
    //         var option = document.createElement("option");
    //         option.value = item.id;
    //         option.textContent = item.shop_name;
    //         selectElement.appendChild(option);
    //     });
    // }

    // function populateDsrOptions(data) {
    //     var selectElement = document.querySelector('select[name="dsr_id"]');
    //     selectElement.innerHTML = "";

    //     data.forEach(function(item) {
    //         var option = document.createElement("option");
    //         option.value = item.id;
    //         option.textContent = item.name;
    //         selectElement.appendChild(option);
    //     });
    // }

</script>

@endpush
