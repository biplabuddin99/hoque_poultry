@extends('layout.app')

@section('pageTitle',trans('Selected Sales Create'))
@section('pageSubTitle',trans('Create'))

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
                        <form method="post" action="{{route(currentUser().'.sales.store')}}" onsubmit="return confirm('are you sure to Save?')">
                            @csrf
                            <div class="row p-2 mt-4">
                                <input type="hidden" name="sales_type" value="1">
                                <div class="col-lg-3 col-md-6 col-sm-12 mt-2">
                                    <label for="cat">{{__('Distributor')}}<span class="text-danger">*</span></label>
                                    @if($user)
                                        <select class="form-select supplier_id select2" name="distributor_id" id="distributor_id" onchange="getDistProduct(product_id,group_id)">
                                            @forelse (App\Models\Settings\Supplier::where(company())->where('id',$user->distributor_id)->get() as $sup)
                                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    @else
                                        <select class="form-select supplier_id select2" name="distributor_id" id="distributor_id" onchange="getDistProduct('product_id','group_id')">
                                            <option value="">Select Distributor</option>
                                            @forelse (App\Models\Settings\Supplier::where(company())->get() as $sup)
                                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>SR</b></label>
                                    <select name="sr_id" id="sruser_id" class=" form-select sruser_id" onchange="show_area();" required>
                                        <option value="">Select</option>
                                        @forelse ($userSr as $p)
                                            <option class="selecet_hide selecet_hide{{$p->distributor_id}}" value="{{$p->id}}">{{$p->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2" id="area">
                                    <label for=""><b>Area</b></label>
                                    <select class="form-select area_id" name="area_id" id="area_name" onchange="shop_area_wise();" required>
                                        <option value="">Select Area</option>
                                        
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Shop/Dsr</b></label>
                                    <select class="form-select" onclick="getShopDsr()" name="select_shop_dsr">
                                        <option value="">Select</option>
                                        <option value="shop">Shop</option>
                                        <option value="dsr">DSR</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2 " id="shopNameContainer" style="display: none;">
                                    <label for=""><b>Shop Name</b></label>
                                    <select class="form-select" name="shop_id" id="shop_deselect">
                                        <option value="">Select Shop</option>
                                        {{-- @forelse ($shops as $p)
                                            <option class="selecet_hide selecet_hide_shop{{$p->sup_id}} {{str_replace(' ', '', $p->area_name)}}" value="{{$p->id}}"> {{$p->shop_name}} ({{$p->area_name}})</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse --}}
                                    </select>
                                </div>

                                <div class="col-lg-5 mt-2" id="dsrNameContainer" style="display: none;">
                                    <div class="row">
                                        <div class="col-8">
                                            <label for=""><b>DSR Name</b></label>
                                            <select class="form-select" name="dsr_id" id="dsr_deselect">
                                                <option value="">Select</option>
                                                @forelse ($userDsr as $p)
                                                    <option class="selecet_hide selecet_hide{{$p->distributor_id}}" value="{{$p->id}}">{{$p->name}}</option>
                                                @empty
                                                    <option value="">No Data Found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label for=""><b>Cash Receive</b></label>
                                            <input type="text" class="form-control receive_dsr_amount" name="receive_amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input type="text" id="datepicker" class="form-control" value="<?php print(date("m/d/Y")); ?>"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                            </div>
                            <!-- table bordered -->
                            
                            <div class="row p-2 mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">{{__('SL')}}</th>
                                                <th scope="col" width="15%">{{__('Group')}}</th>
                                                <th scope="col" width="20%">{{__('Product Name')}}</th>
                                                <th scope="col" width="7%">{{__('CTN')}}</th>
                                                <th scope="col" width="7%">{{__('PCS')}}</th>
                                                <th scope="col">{{__('Tp/Tpfree')}}</th>
                                                {{-- <th scope="col">{{__('CTN Price')}}</th> --}}
                                                <th scope="col" width="10%">{{__('Total Pcs')}}</th>
                                                <th scope="col">{{__('PCS Price')}}</th>
                                                <th scope="col">{{__('Sub-Total')}}</th>
                                                <th scope="col">{{__('Stock(QTY)')}}</th>
                                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                            </tr>
                                        </thead>
                            @php
                                $lastSl = 0;
                                $sl = 0;
                            @endphp
                                        <tbody id="sales_repeat">
                                            <tr>
                                                <td>{{++$sl}}</td>
                                                <td>
                                                    <select class="select2 group_product" id="group_id" name="group_id[]" onchange="groupWiseProductShow(this);">
                                                        <option value="">Select Group</option>
                                                       
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control form-select product_id" id="product_id" onchange="productData(this);" name="product_id[]">
                                                        <option value="">Select Product</option>
                                                       
                                                    </select>
                                                </td>
                                                <td><input class="form-control ctn" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="ctn[]" value=""></td>
                                                <td><input class="form-control pcs" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="pcs[]"value=""></td>
                                                <td>
                                                    <select class="form-select select_tp_tpfree" name="select_tp_tpfree[]" onchange="productData(this);">
                                                        <option value="2">TP Free</option>
                                                        <option value="1">TP</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control ctn_price" type="hidden" name="ctn_price[]" value="" >
                                                    <input class="form-control totalquantity_pcs" type="text" value="">
                                                </td>
                                                <td><input class="form-control per_pcs_price" type="text" name="per_pcs_price[]" value="" onkeyup="manulPcsPrice(this);" ></td>
                                                <td>
                                                    <input class="form-control subtotal_price" type="text" name="subtotal_price[]" value="">
                                                    <input class="form-control totalquantity_pcs" type="hidden" name="totalquantity_pcs[]" value="">
                                                </td>
                                                <td class="show_stock"></td>
                                                <td>
                                                    <span onClick='addRow();' class="add-row text-primary ms-3"><i class="bi bi-plus-square-fill"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total</th>
                                                <th class="totalctn" id="totalCtn"></th>
                                                <th class="totalPcs" id="totalPcs"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-5 mt-2 text-end">
                                            <label for="" class="form-group"><h4>Total</h4></label>
                                        </div>
                                        <div class="col-lg-2 mt-2 text-end" style="margin-left: 3rem!important;">
                                            <label for="" class="form-group"><h5 class="total" id="grandTotal">0.00</h5></label>
                                            <input type="hidden" name="total" class="total_p" id="grandTotalP">
                                        </div>
                                        <div class="col-lg-3"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6" id="oldDueContainer" style="display: none;">
                                            <table class="table">
                                                <thead>
                                                    <tr class="text-center table-bordered">
                                                        <th width=65%>Shop Name</th>
                                                        <th>Due</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="shop-due">
                                                    <tr>
                                                        <td>
                                                            <select name="old_due_shop_id[]" class="form-control form-select due_shop" id="deselect_shop_due" onchange="getShopDue(this);">
                                                                <option value="">Select Shop</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control shop_due_amount text-end" name="due_amount[]"></td>
                                                        <td class="text-center"><span onClick='addShop();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span></td>
                                                    </tr>
                                                </tbody>
                                                @php
                                                    $lastSl = $sl;
                                                @endphp
                                            </table>
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
     /* call on load page */
     $(document).ready(function(){
        $('.selecet_hide').hide();
    })
    function groupWiseProductShow(e){
        let group_id= $(e).val();
        $(e).closest('tr').find('.selecet_p_hide').hide();
        $(e).closest('tr').find('.selecet_p_hide'+group_id).show();
    }
    
</script>
<script>
    let old_supplier_id=0;
    let opt="";
    let optGroup="";
    function getDistProduct(product_id,group_id) {
        let supplier_id=$('.supplier_id').val();
        
        $('.selecet_hide').hide();
        $('.selecet_hide'+supplier_id).show();
        
        if(old_supplier_id!=supplier_id){
            $('#sruser_id').prop('selectedIndex', 0);
            $('#dsr_deselect').prop('selectedIndex', 0);
            $('#shop_deselect').prop('selectedIndex', 0);
            $('#area_name').prop('selectedIndex', 0);
            $('#deselect_shop_due').prop('selectedIndex', 0);
            $('.old_tr_remove').closest('tr').remove();
            $('#product_id').closest('tr').find('.ctn').val('');
            $('#product_id').closest('tr').find('.pcs').val('');
            $('#product_id').closest('tr').find('.ctn_price').val('');
            $('#product_id').closest('tr').find('.per_pcs_price').val('');
            $('#product_id').closest('tr').find('.subtotal_price').val('');
            $('#product_id').closest('tr').find('.show_stock').text('');
            $('#deselect_shop_due').closest('tr').find('.shop_due_amount ').val('');
            $('.receive_dsr_amount').val('');
            $('#grandTotal').text('0');
            $('#grandTotalP').val('0');
            $.ajax({
                url: "{{ route(currentUser().'.get_selected_supplier_product') }}",
                type: "GET",
                dataType: "json",
                data:{supplier_id:supplier_id},
                success: function(datas) {
                    console.log(datas);
                    opt=`<option value="">Select Product</option>`;
                    optGroup=`<option value="">Select Group</option>`;
                    if(datas.data.length > 0){
                        for(d of datas.data){
                            opt+=`<option class="selecet_p_hide selecet_p_hide${d?.product?.group_id}" data-tp='${d?.product?.tp_price}' data-tp_free='${d?.product?.tp_free}' value="${d?.product?.id}">${d?.product?.product_name}</option>`;
                        }
                        for(d of datas.group){
                            optGroup+=`<option value="${d.id}">${d.name}</option>`;
                        }
                    }
                    $('#'+product_id).html(opt);
                    $('#'+group_id).html(optGroup);
                },
                error: function(xhr, status, error) {
                    return data="";
                }
            });
           
            old_supplier_id=supplier_id;
        }
        
        $('#'+product_id).html(opt);
        $('#'+group_id).html(optGroup);
        
    }

    let counter = 0;
    let countSl = {{ $lastSl }};

    $(document).ready(function() {
        // Attach the change event to all current and future .ctn input fields
        $('#sales_repeat').on('input', '.ctn', function() {
            calculateTotalCtn();
        });
        $('#sales_repeat').on('input', '.pcs', function() {
            calculateTotalPcs();
        });
        
    });
    // Function to calculate total Ctn
    function calculateTotalCtn() {
        let totalCtn = 0;
        $('#sales_repeat .ctn').each(function() {
            const ctntotal = parseFloat($(this).val()) || 0;
            totalCtn += ctntotal;
        });
        $('#totalCtn').text(totalCtn);
    };
    function calculateTotalPcs() {
        let totalPcs = 0;
        $('#sales_repeat .pcs').each(function() {
            const pcs = parseFloat($(this).val()) || 0;
            totalPcs += pcs;
        });
        $('#totalPcs').text(totalPcs);
    };

    function addRow(){
        countSl++;
        var row=`
        <tr>
            <td>${countSl}</td>
            <td>
                <select class="select2 group_product" id="group_id_${counter}" name="group_id[]" onchange="groupWiseProductShow(this);">
                    <option value="">Select Group</option>
                    
                </select>
            </td>
            <td>
                <select class="old_tr_remove form-control form-select product_id" id="product_id_${counter}" name="product_id[]" onchange="productData(this);">
                    <option value="">Select Productd</option>
                    
                </select>
            </td>
            <td><input class="form-control ctn" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="ctn[]" value=""></td>
            <td><input class="form-control pcs" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);" type="text" name="pcs[]"value=""></td>
            <td>
                <select class="form-select select_tp_tpfree" name="select_tp_tpfree[]" onchange="productData(this);">
                    <option value="2">TP Free</option>
                    <option value="1">TP</option>
                </select>
            </td>
            <td>
                <input class="form-control ctn_price" type="hidden" name="ctn_price[]" value="">
                <input class="form-control totalquantity_pcs" type="text" value="">
            </td>
            <td><input class="form-control per_pcs_price" name="per_pcs_price[]" type="text" value="" onkeyup="manulPcsPrice(this);"></td>
            <td>
                <input class="form-control subtotal_price" type="text" name="subtotal_price[]" value="">
                <input class="form-control totalquantity_pcs" type="hidden" name="totalquantity_pcs[]" value="">
            </td>
            <td class="show_stock"></td>
            <td class="text-center">
                <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
            </td>
        </tr>`;
        $('#sales_repeat').append(row);
        
        getDistProduct('product_id_'+counter,'group_id_'+counter);
        //$(`#product_id_${counter}`);
        $(`#group_id_${counter}`).select2();
        // $(`#product_id_${counter}`).select2();
        counter++;
        calculateTotalCtn();
        calculateTotalPcs();
    }
    function addShop() {
        var row = `
        <tr>
            <td>
                <select name="old_due_shop_id[]" class="old_tr_remove old_shop_tr_remove form-control form-select old_tr_remove due_shop" onchange="getShopDue(this);">
                    <option value="">Select Shop</option>
                </select>
            </td>
            <td><input type="text" class="form-control shop_due_amount text-end" name="due_amount[]"></td>
            <td class="text-center">
                <span onClick='removeShop(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
            </td>
        </tr>`;
        $('#shop-due').append(row);
        shop_area_wise();
    }


    function removeRow(e){
        if (confirm("Are you sure you want to remove this row?")) {
            $(e).closest('tr').remove();
            total_calculate();
            calculateTotalCtn();
            calculateTotalPcs();
        }
    }
    function removeShop(e){
        if (confirm("Are you sure you want to remove this row?")) {
            $(e).closest('tr').remove();
        }
    }

    function productData(e) {
        var selectedOption = parseInt($(e).closest('tr').find('.select_tp_tpfree').val());
        //console.log(selectedOption)
        var tp = $(e).closest('tr').find('.product_id option:selected').attr('data-tp');
        var tpFree = $(e).closest('tr').find('.product_id option:selected').attr('data-tp_free');
        var productId = $(e).closest('tr').find('.product_id option:selected').val() ? parseInt($(e).closest('tr').find('.product_id option:selected').val()) : 0;
        var ctn = $(e).closest('tr').find('.ctn').val() ? parseFloat($(e).closest('tr').find('.ctn').val()) : 0;
        var pcs = $(e).closest('tr').find('.pcs').val() ? parseFloat($(e).closest('tr').find('.pcs').val()) : 0;
        var perPcsPrice = $(e).closest('tr').find('.per_pcs_price').val() ? parseFloat($(e).closest('tr').find('.per_pcs_price').val()) : 0;
        
        if(productId == 0){
            $(e).closest('tr').find('.ctn').val('');
            $(e).closest('tr').find('.pcs').val('');
            $(e).closest('tr').find('.ctn_price').val('');
            $(e).closest('tr').find('.per_pcs_price').val('');
            $(e).closest('tr').find('.subtotal_price').val('');
        }else{
            $.ajax({
                url: "{{route(currentUser().'.sales_unit_data_get')}}",
                type: "GET",
                dataType: "json",
                data: { product_id: productId },
                success: function (data) {
                    // this function have doController UnitDataGet return qty
                    console.log(data)
                    // if(perPcsPrice){
                    //     alert(perPcsPrice)
                    //     tp = perPcsPrice;
                    // }else{
                    //     alert('tp')
                    // }
                    
                    let totalqty=((data.unit*ctn)+pcs);
                    $(e).closest('tr').find('.totalquantity_pcs').val(totalqty);
                    $(e).closest('tr').find('.show_stock').text(data.showqty);
                    if(data.unit){
                        //let pcstp=parseFloat(tp / data.unit).toFixed(2);
                        // tp = perPcsPrice?perPcsPrice:tp;
                        // tpFree = perPcsPrice?perPcsPrice:tpFree;
                        let ctnTp=parseFloat(tp * data.unit).toFixed(2);
                        //let pcstpFree=parseFloat(tpFree / data.unit).toFixed(2);
                        let ctntpFree=parseFloat(tpFree * data.unit).toFixed(2);
                        let tpCtnPrice = parseFloat(ctnTp * ctn);
                        let tpPcsPrice = parseFloat(tp * pcs);
                        let tpFreeCtnPrice = parseFloat((tpFree * data.unit) * ctn);
                        let tpFreePcsPrice = parseFloat(tpFree * pcs);
                        var TpSubtotal = parseFloat(totalqty*tp).toFixed(2);
                        console.log(TpSubtotal);
                        var TpFreeSubtotal = parseFloat(tpFreeCtnPrice+ tpFreePcsPrice).toFixed(2);

                        if (selectedOption == 1) {
                            $(e).closest('tr').find('.ctn_price').val(ctnTp);
                            $(e).closest('tr').find('.per_pcs_price').val(tp);
                            $(e).closest('tr').find('.subtotal_price').val(TpSubtotal);
                        } else if (selectedOption == 2) {
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

    }
    function manulPcsPrice(e){
        var totalQty = $(e).closest('tr').find('.totalquantity_pcs').val() ? parseFloat($(e).closest('tr').find('.totalquantity_pcs').val()) : 0;
        var perPcsPrice = $(e).closest('tr').find('.per_pcs_price').val() ? parseFloat($(e).closest('tr').find('.per_pcs_price').val()) : 0;
        var subTotal = perPcsPrice*totalQty;
        $(e).closest('tr').find('.subtotal_price').val(subTotal);
        total_calculate();
    }

    function total_calculate() {
        var subtotal = 0;
        $('.subtotal_price').each(function() {
            subtotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
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
        var oldDueShopContainer = document.getElementById("oldDueContainer");

        if (selectedOption === "shop") {
            shopNameContainer.style.display = "block";
            dsrNameContainer.style.display = "none";
            oldDueShopContainer.style.display = "none";
            $('#dsr_deselect').prop('selectedIndex', 0);
            $('.old_shop_tr_remove').closest('tr').remove();
            $('.receive_dsr_amount').val('');
            $('.shop_due_amount').val('');
            // getShopData();
        } else if (selectedOption === "dsr") {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "block";
            oldDueShopContainer.style.display = "block";
            $('#shop_deselect').prop('selectedIndex', 0);
            // getDsrData();
        } else {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "none";
            oldDueShopContainer.style.display = "none";
        }
    }
    function show_area() {
        let sruser_id=$('.sruser_id').val();
        $.ajax({
            url: "{{ route(currentUser().'.get_area') }}",
            type: "GET",
            dataType: "json",
            data:{sruser_id:sruser_id},
            success: function(data) {
                console.log(data);
                let optArea = `<option value="">Select Area</option>`;
                if (data.length > 0) {
                    data.forEach(item => {
                        optArea += `<option value="${item.id}">${item.name}</option>`;
                    });
                }
                $('#area_name').html(optArea);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }
    function shop_area_wise() {
        let area_id = $('.area_id').val();
        $.ajax({
            url: "{{ route(currentUser().'.get_area_shop') }}",
            type: "GET",
            dataType: "json",
            data: {area_id: area_id},
            success: function(data) {
                console.log(data);
                let optShop = `<option value="">Select Shop</option>`;
                if (data.length > 0) {
                    data.forEach(item => {
                        optShop += `<option value="${item.id}">${item.shop_name}</option>`;
                    });
                }
                $('#shop_deselect').each(function() {
                    let selectedValue = $(this).val();
                    $(this).html(optShop);
                    $(this).val(selectedValue); // Reselect the previously selected value
                });
                $('.due_shop').each(function() {
                    let selectedValue = $(this).val();
                    $(this).html(optShop);
                    $(this).val(selectedValue); // Reselect the previously selected value
                });
                
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    function getShopDue(e) {
        let shop_id= $(e).val();
        console.log(shop_id);
        $.ajax({
            url: "{{ route(currentUser().'.get_shop_due') }}",
            type: "GET",
            dataType: "json",
            data:{shop_id:shop_id},
            success: function(data) {
                //console.log(data);
                var due = (data < 0)? Math.abs(data): '0';
                $(e).closest('tr').find('.shop_due_amount').val(due);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
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
