@extends('layout.app')

@section('pageTitle',trans('Sales Create'))
@section('pageSubTitle',trans('Create'))

@section('content')
<style>
    @media (min-width: 1192px){
        .select2{
            width: 260px !important;
        }
    }
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 100% !important;
    }
    .custom-modal .modal-dialog {
        max-width: 500px;
        margin: auto;
        margin-top: 150px;
    }
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" action="{{route(currentUser().'.sales.store')}}" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            <div class="row p-2 mt-4">
                                <input type="hidden" name="sales_type" value="0">
                                <div class="col-lg-3 col-md-6 col-sm-12 mt-2">
                                    <label for="cat">{{__('Distributor')}}<span class="text-danger">*</span></label>
                                    @if($user)
                                        <select class="form-select supplier_id" name="distributor_id" onchange="getProduct();">
                                            @forelse (App\Models\Settings\Supplier::where(company())->where('id',$user->distributor_id)->get() as $sup)
                                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    @else
                                        <select class="form-select supplier_id" name="distributor_id" onchange="getProduct();">
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
                                    <label for=""><b>Shop/Dsr</b></label>
                                    <select class="form-select" onclick="getShopDsr()" name="select_shop_dsr">
                                        <option value="">Select</option>
                                        <option value="shop">Shop</option>
                                        <option value="dsr">DSR</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2" id="shopNameContainer" style="display: none;">
                                    <label for=""><b>Shop Name</b></label>
                                    <select class="select2 form-select shop_id" name="shop_id" id="shop_deselect">
                                        <option value="">Select shop</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2" id="dsrNameContainer" style="display: none;">
                                    <label for=""><b>DSR Name</b></label>
                                    <select class="select2 form-select dsr_id" name="dsr_id" id="dsr_deselect">
                                        <option value="">Select dsr</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>SR</b></label>
                                    <select name="sr_id" class="select2 form-select"  id="sruser_id">
                                        <option value="">Select sr</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input type="text" id="datepicker" class="form-control" value="<?php print(date("m/d/Y")); ?>"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                            </div>
                            <!-- table bordered -->
                            <div class="row p-2 mt-4">
                                <div class="table-responsive d-none show_click">
                                    <table class="table table-bordered mb-0 table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col" width="30%">{{__('Product Name')}}</th>
                                                <th scope="col">{{__('CTN')}}</th>
                                                <th scope="col">{{__('PCS')}}</th>
                                                <th scope="col">{{__('Tp/Tpfree')}}</th>
                                                <th scope="col">{{__('CTN Price')}}</th>
                                                <th scope="col">{{__('PCS Price')}}</th>
                                                <th scope="col">{{__('Sub-Total')}}</th>
                                                <th scope="col">{{__('Stock(QTY)')}}</th>
                                                {{--  <th class="white-space-nowrap">{{__('ACTION')}}</th>  --}}
                                            </tr>
                                        </thead>
                                        {{--  @php
                                            $showqty =\App\Models\Stock\Stock::whereIn('status', [1, 2, 3])->sum('totalquantity_pcs') - \App\Models\Stock\Stock::whereIn('status', [0, 4, 5])->sum('totalquantity_pcs');
                                        @endphp  --}}
                                        <tbody id="sales_repeat" class="sales_repeat">
                                        </tbody>
                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-5 mt-2 text-end">
                                            <label for="" class="form-group"><h4>Total</h4></label>
                                        </div>
                                        <div class="col-lg-2 mt-2 text-end" style="margin-left: 3rem!important;">
                                            <label for="" class="form-group"><h5 class="total">0.00</h5></label>
                                            <input type="hidden" name="total" class="total_p">
                                        </div>
                                        <div class="col-lg-3"></div>
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
    let old_supplier_id=0;
    function getProduct(e){
        var SuplierId=$('.supplier_id').val();
        let counter = 0;
        if(old_supplier_id!=SuplierId){
            $.ajax({
                url: "{{route(currentUser().'.get_supplier_product')}}",
                type: "GET",
                dataType: "json",
                data: { supplier_id:SuplierId },
                success: function(productdata) {
                    console.log(productdata);
                    let selectElement = $('.sales_repeat');
                        selectElement.empty();
                        $.each(productdata, function(index, value) {
                            selectElement.append(
                                `<tr>
                                    <td>
                                        <input readonly class="form-control" type="text" value="${value.product.product_name}" placeholder="">
                                        <input readonly class="form-control product_id" type="hidden" name="product_id[]" value="${value.product.id}">
                                        <input readonly class="form-control tp_price" type="hidden" value="${value.product.tp_price}">
                                        <input readonly class="form-control tp_free" type="hidden" value="${value.product.tp_free}">
                                        {{--  <select class="choices form-select product_id" id="product_id" name="product_id[]">
                                            <option value="">Select Product</option>
                                            @forelse (\App\Models\Product\Product::where(company())->get(); as $pro)
                                            <option  data-tp='{{ $pro->tp_price }}' data-tp_free='{{ $pro->tp_free }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                                            @empty
                                            @endforelse
                                        </select>  --}}
                                    </td>
                                    <td><input class="form-control ctn" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);"  type="text" name="ctn[]" value="" placeholder="ctn"></td>
                                    <td><input class="form-control pcs" onkeyup="productData(this);" onblur="productData(this);" onchange="productData(this);"  type="text" name="pcs[]"value="" placeholder="pcs"></td>
                                    <td>
                                        <select class="form-select select_tp_tpfree" name="select_tp_tpfree[]" onchange="productData(this);">
                                            <option value="1">TP</option>
                                            <option value="2">TP Free</option>
                                        </select>
                                    </td>
                                    <td><input class="form-control ctn_price" type="text" name="ctn_price[]" value="" placeholder="Tp Price"></td>
                                    <td>
                                        <div class="d-flex">
                                            <input readonly class="form-control per_pcs_price" name="per_pcs_price[]" type="text" value="" placeholder="PCS Price">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal${counter}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <div class="modal fade custom-modal" id="exampleModal${counter}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content p-3">
                                                        <form onsubmit="return productForm(this)">
                                                            <input type="hidden" name="product_id" value="${value.product.id}" class="modalProductId">
                                                            <input type="hidden" value="${counter}" class="rowID">
                                                            <div class="form-group">
                                                                <label for="modalProductName">Product Name : ${value.product.product_name}</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="modalTpPrice">TP Price</label>
                                                                <input type="text" name="tp_price" class="form-control modalTpPrice" value="${value.product.tp_price}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input class="form-control subtotal_price" type="text" name="subtotal_price[]" value="" placeholder="Sub-Total">
                                        <input class="form-control totalquantity_pcs" type="hidden" name="totalquantity_pcs[]" value="">
                                    </td>
                                    <td>${value.showqty}</td>
                                    {{--  <td>
                                        <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
                                        <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>
                                    </td>  --}}
                                </tr>`
                            );
                            counter++;
                        });
                },
            });
            old_supplier_id=SuplierId;
            getShopData();
        }
        $('.show_click').removeClass('d-none');
     };


$(document).ready(function() {
    // Open modal with current row data
    $(document).on('click', '.bi-pencil-square', function() {
        var currentRow = $(this).closest('tr');
        var productId = currentRow.find('.product_id').val();
        var productName = currentRow.find('input[type="text"]').val();
        var tpPrice = currentRow.find('.tp_price').val();
        $('#modalProductId').val(productId);
        $('#modalProductName').val(productName);
        $('#modalTpPrice').val(tpPrice);
        $('#exampleModal').modal('show');
    });
});
    function productForm(e) {
        let updatedTpPrice = $(e).find('.modalTpPrice').val();
        let productId = $(e).find('.modalProductId').val();
        let rowID = $(e).find('.rowID').val();
        let currentRow = $('.product_id[value="' + productId + '"]').closest('tr');
        
        $.ajax({
            url: '{{ route('owner.update_product_price') }}', // Corrected this line
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tp_price: updatedTpPrice,
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    currentRow.find('.tp_price').val(updatedTpPrice);
                    $('#exampleModal'+rowID).modal('hide');
                } else {
                    console.error('Failed to update price:', response.message);
                }
                return false;
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                return false;
            }
        });
        return false;
    }

    function addRow(){

        var row=`
        <tr>
            <td>
                <select class="choices form-select product_id" id="product_id" name="product_id[]">
                    <option value="">Select Product</option>
                    @forelse (\App\Models\Product\Product::where(company())->get() as $pro)
                    <option  data-tp='{{ $pro->tp_price }}' data-tp_free='{{ $pro->tp_free }}' value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                    @empty
                    @endforelse
                </select>
            </td>
            <td><input class="form-control ctn"  onblur="productData(this);" onchange="productData(this);" onkeyup="productData(this);" type="text" name="ctn[]" value="" placeholder="ctn"></td>
            <td><input class="form-control pcs"  onblur="productData(this);" onchange="productData(this);"  onkeyup="productData(this);" type="text" name="pcs[]"value="" placeholder="pcs"></td>
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
                {{--  <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>  --}}
                {{--  <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>  --}}
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

    function productData(e) {
        var selectedOption = parseInt($(e).closest('tr').find('.select_tp_tpfree').val());
        //console.log(selectedOption)
        var tp = $(e).closest('tr').find('.tp_price').val();
        var tpFree = $(e).closest('tr').find('.tp_free').val();
        var productId = $(e).closest('tr').find('.product_id').val();
        //var productId = parseInt($(e).closest('tr').find('.product_id option:selected').val());
        //var tp = $(e).closest('tr').find('.product_id option:selected').attr('data-tp');
        //var tpFree = $(e).closest('tr').find('.product_id option:selected').attr('data-tp_free');
        var ctn = $(e).closest('tr').find('.ctn').val() ? parseFloat($(e).closest('tr').find('.ctn').val()) : 0;
        var pcs = $(e).closest('tr').find('.pcs').val() ? parseFloat($(e).closest('tr').find('.pcs').val()) : 0;
        $.ajax({
            url: "{{route(currentUser().'.unit_data_get')}}",
            type: "GET",
            dataType: "json",
            data: { product_id: productId },
            success: function (data) {
                // this function have doController UnitDataGet return qty
                //UPDATE `temporary_sales_details` SET `totalquantity_pcs`=(`subtotal_price`/`pcs_price`) WHERE `totalquantity_pcs`=0 AND subtotal_price>0
                console.log(data);
                let totalqty=((data*ctn)+pcs);
                console.log(totalqty);
                $(e).closest('tr').find('.totalquantity_pcs').val(totalqty);
                if(data){
                    let ctnTp=parseFloat(tp * data).toFixed(2);
                    let ctntpFree=parseFloat(tpFree * data).toFixed(2);
                    let tpCtnPrice = parseFloat(ctnTp * ctn);
                    let tpPCSPrice = parseFloat(tp * pcs);
                    let tpFreeCtnPrice = parseFloat((tpFree * data) * ctn);
                    let tpFreePcsPrice = parseFloat(tpFree * pcs);
                    var TpSubtotal = parseFloat(tpPCSPrice + tpCtnPrice).toFixed(2);
                    var TpFreeSubtotal = parseFloat(tpFreePcsPrice + tpFreeCtnPrice).toFixed(2);

                    if (selectedOption === 1) {
                        //$(e).closest('tr').find('.ctn_price').val(tp);
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
        var shopDropdown = document.querySelector('select[name="shop_id"]');
        var dsrNameContainer = document.getElementById("dsrNameContainer");
        var dsrDropdown = document.querySelector('select[name="dsr_id"]');

        if (selectedOption === "shop") {
            shopNameContainer.style.display = "block";
            dsrNameContainer.style.display = "none";
            dsrDropdown.value = "";
            //getShopData();
        } else if (selectedOption === "dsr") {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "block";
            shopDropdown.value = "";
            //getDsrData();
        } else {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "none";
        }
    }
    function getShopData() {
        let supplier_id=$('.supplier_id').val();
        $.ajax({
            url: "{{ route(currentUser().'.get_shop') }}",
            type: "GET",
            dataType: "json",
            data:{supplier_id:supplier_id},
            success: function(data) {
                //console.log(data);
                let optShop = `<option value="">Select Shop</option>`;
                let optDsr = `<option value="">Select Dsr</option>`;
                let optSr = `<option value="">Select Sr</option>`;
                if(data.length > 0){
                    data.forEach(item => {
                        item.shop.forEach(shop => {
                            optShop += `<option value="${shop.id}">${shop.shop_name}(${shop.area_name})</option>`;
                        });
                    });
                }
                if(data.length > 0){
                    data.forEach(item => {
                        item.dsr.forEach(dsr => {
                            optDsr += `<option value="${dsr.id}">${dsr.name}</option>`;
                        });
                    });
                }
                if(data.length > 0){
                    data.forEach(item => {
                        item.sr.forEach(sr => {
                            optSr += `<option value="${sr.id}">${sr.name}</option>`;
                        });
                    });
                }
                $('#shop_deselect').html(optShop);
                $('#dsr_deselect').html(optDsr);
                $('#sruser_id').html(optSr);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

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

$(document).ready(function() {
    // Event listener for save changes button in modal
    $('#saveChanges').click(function() {
        // Get updated TP Price from modal
        const updatedTpPrice = $('#modalTpPrice').val();
        // AJAX request to update product in database
        $.ajax({
            url: '/update.product', // Your update route here
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                tp_price: updatedTpPrice
            },
            success: function(response) {
                // Update TP Price in the table row
                currentRow.find('.form-control[name="ctn_price[]"]').val(updatedTpPrice);
                
                // Close modal
                $('#exampleModal').modal('hide');
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Handle error
            }
        });
    });
});

</script>

@endpush
