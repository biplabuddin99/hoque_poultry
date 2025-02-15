@extends('layout.app')

@section('pageTitle',trans('Receive Do Update'))
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
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" action="{{route(currentUser().'.do.accept_do_update')}}" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            <input type="hidden" name="do_rec_uni_code" value="{{$recIndex->do_rec_uni_code}}">
                            <div class="row p-2 mt-4">
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Stock Date</b></label>
                                    <input type="text" required id="datepicker" class="form-control" value="{{$recIndex->stock_date}}"  name="stock_date" placeholder="mm-dd-yyyy">
                                </div>

                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Chalan NO<span class="text-danger">*</span></b></label>
                                    <input onkeyup="removeCharacter(this)" type="text" id="" required class="form-control" value="{{$recIndex->chalan_no}}"  name="chalan_no" placeholder="Chalan NO">
                                </div>

                                <div class="col-lg-3 mt-2">
                                    <label for="lcNo"><b>Distributor<span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control" value="{{$recIndex->supplier?->name}}">
                                </div>
                                <div class="col-lg-3 mt-2 d-none">
                                    <label for="lcNo"><b>Distributor<span class="text-danger">*</span></b></label>
                                    <select name="distributor_id" class="select2 form-select supplier_id" onchange="getDistProduct('product_id');" required>
                                        <option value="">Select</option>
                                        @forelse ($distributors as $d)
                                            <option value="{{$d->id}}" {{ $recIndex->distributor_id == $d->id? 'selected' : '' }}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <!-- table bordered -->
                            <div class="row p-2 mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col" width="30%">{{__('Product Name')}}</th>
                                                {{--  <th scope="col">{{__('Lot Number')}}</th>  --}}
                                                <th scope="col">{{__('Do Referance')}}</th>
                                                <th scope="col">{{__('CTN')}}</th>
                                                <th scope="col">{{__('PCS')}}</th>
                                                <th scope="col">{{__('Free')}}</th>
                                                <th scope="col">{{__('receive')}}</th>
                                                <th scope="col">{{__('Dp(CTN)')}} <span class="text-danger">*</span></th>
                                                <th scope="col">{{__('Dp(PCS)')}}</th>
                                                <th scope="col">{{__('SubTotal-Dp')}}</th>
                                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="product">
                                            @foreach ($data as $d)
                                                <tr>
                                                    <td>
                                                        <select class="select2 product_id" id="product_id{{$d->id}}" onchange="doData(this);" name="product_id[]">
                                                            <option value="">Select Product</option>
                                                            @foreach ($products as $p)
                                                                <option data-dp='{{$p->dp_price}}' data-tp='{{$p->tp_price}}' data-tp_free='{{$p->tp_free}}' data-mrp='{{$p->mrp_price}}' value="{{$p->id}}" {{$d->product_id == $p->id? 'selected' : ''}}>{{$p->product_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" class="tp_price" name="tp_price[]" value="{{$d->tp_price}}">
                                                        <input type="hidden" class="tp_free" name="tp_free[]" value="{{$d->tp_free}}">
                                                        <input type="hidden" class="mrp_price" name="mrp_price[]" value="{{$d->mrp_price}}">
                                                    </td>
                                                    <td>
                                                        <select class="form-select referance_number" value="" name="do_id[]">
                                                            <option selected value="{{$d->do_id}}" data-dodetail_id="{{$d->do_detail_id}}">{{$d->do?->reference_num}}</option>
                                                        </select>
                                                        <input type="hidden" class="dodetail_id" name="dodetail_id[]" value="{{$d->do_detail_id}}">
                                                    </td>
                                                    <td><input class="form-control ctn" type="text" name="ctn[]" onkeyup="getCtnQty(this)" value="{{$d->ctn}}" placeholder="ctn"></td>
                                                    <td><input class="form-control pcs" type="text" name="pcs[]" onkeyup="getCtnQty(this)" value="{{$d->pcs}}" placeholder="pcs"></td>
                                                    <td><input class="form-control free_pcs" type="text" name="free[]" onkeyup="getCtnQty(this)" value="{{$d->quantity_free}}" placeholder="free"></td>
                                                    <input type="hidden" name="old_free_receive[]" value="{{$d->quantity_free}}">
                                                    <td><input class="form-control receive" type="text" name="receive[]" value="{{$d->totalquantity_pcs}}" placeholder="receive"></td>
                                                    <input type="hidden" name="old_receive[]" value="{{$d->totalquantity_pcs}}">
                                                    <td><input required class="form-control dp" type="text" onkeyup="getCtnQty(this)" name="dp[]" value="{{$d->dp_price}}" placeholder="dp"></td>
                                                    <td><input readonly class="form-control dp_pcs" type="text" name="dp_pcs[]" value="{{$d->dp_pcs}}" placeholder="dp PCS"></td>
                                                    <td><input class="form-control subtotal_dp_pcs" type="text" name="subtotal_dp_pcs[]" value="{{$d->subtotal_dp_pcs}}" placeholder="total-dp-price"></td>
                                                    <td>
                                                         <span onClick='removeRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span> 
                                                        <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="8" class="text-end">Total</th>
                                                <th class="text-center">
                                                    <span class="total_dp"></span>
                                                </th>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    let old_supplier_id=0;
    let opt="";
    function getDistProduct(product_id) {
        let supplier_id=$('.supplier_id').val();

        if(old_supplier_id!=supplier_id){
            $('.old_tr_remove').closest('tr').remove();
            $.ajax({
                url: "{{ route(currentUser().'.get_supplier_product') }}",
                type: "GET",
                dataType: "json",
                data:{supplier_id:supplier_id},
                success: function(data) {
                    opt=`<option value="">Select Product</option>`;
                    if(data.length > 0){
                        for(d of data){
                            opt+=`<option data-dp='${d?.product?.dp_price}' data-tp='${d?.product?.tp_price}' data-tp_free='${d?.product?.tp_free}' data-mrp='${d?.product?.mrp_price}' value="${d?.product?.id}">${d?.product?.product_name}</option>`;
                        }
                    }
                    $('#'+product_id).html(opt)
                },
                error: function(xhr, status, error) {
                    return data="";
                }
            });
           
            old_supplier_id=supplier_id;
        }
        
        $('#'+product_id).html(opt)
        
    }


    let counter = 0;
    function addRow(){
var row=`<tr>
    <td>
        <select class="select2 product_id old_tr_remove" id="product_id_${counter}" onchange="doData(this);" name="product_id[]">
            <option value="">Select Product</option>
            @foreach ($products as $p)
                <option data-dp='{{$p->dp_price}}' data-tp='{{$p->tp_price}}' data-tp_free='{{$p->tp_free}}' data-mrp='{{$p->mrp_price}}' value="{{$p->id}}">{{$p->product_name}}</option>
            @endforeach
        </select>
        <input type="hidden" class="tp_price" name="tp_price[]">
        <input type="hidden" class="tp_free" name="tp_free[]">
        <input type="hidden" class="mrp_price" name="mrp_price[]">
    </td>
    <td>
        <select class="form-select referance_number" name="do_id[]">
        </select>
        <input type="hidden" class="dodetail_id" name="dodetail_id[]">
    </td>
    <td><input class="form-control ctn" type="text" name="ctn[]" onkeyup="getCtnQty(this)" value="" placeholder="ctn"></td>
    <td><input class="form-control pcs" type="text" name="pcs[]" onkeyup="getCtnQty(this)" value="" placeholder="pcs"></td>
    <td><input class="form-control free_pcs" type="text" name="free[]" onkeyup="getCtnQty(this)" value="" placeholder="free"></td>
    <input type="hidden" name="old_free_receive[]" value="0">
    <td><input class="form-control receive" type="text" name="receive[]" value="" placeholder="receive"></td>
    <input type="hidden" name="old_receive[]" value="0">
    <td><input required class="form-control dp" type="text" onkeyup="getCtnQty(this)" name="dp[]" value="" placeholder="dp"></td>
    <td><input readonly class="form-control dp_pcs" type="text" name="dp_pcs[]" value="" placeholder="dp PCS"></td>
    <td><input class="form-control subtotal_dp_pcs" type="text" name="subtotal_dp_pcs[]" value="" placeholder="total-dp-price"></td>
    <td>
        <span onClick='RemoveRow(this);' class="delete-row text-danger"><i class="bi bi-trash-fill"></i></span>
        {{--  <span onClick='addRow();' class="add-row text-primary"><i class="bi bi-plus-square-fill"></i></span>  --}}
    </td>
</tr>`;
    $('#product').append(row);
    //getDistProduct('product_id_'+counter);
    $(`#product_id_${counter}`).select2();
    counter++;
    //console.log(counter)
}

function RemoveRow(e) {
    if (confirm("Are you sure you want to remove this row?")) {
        $(e).closest('tr').remove();
        total_calculate();
    }
}

</script>
<script>
    function doData(e) {
        let product_id = $(e).closest('tr').find('.product_id').val();
        let cn = $(e).closest('tr').find('.ctn').val() ? parseFloat($(e).closest('tr').find('.ctn').val()) : 0;
        let dp=$(e).find('option:selected').data('dp');
        $(e).closest('tr').find('.dp_pcs').val(dp);
        let tpPrice = $(e).closest('tr').find('.product_id option:selected').attr('data-tp');
        $(e).closest('tr').find('.tp_price').val(tpPrice);
        let tpFree = $(e).closest('tr').find('.product_id option:selected').attr('data-tp_free');
        $(e).closest('tr').find('.tp_free').val(tpFree);
        let mrpPrice = $(e).closest('tr').find('.product_id option:selected').attr('data-mrp');
        $(e).closest('tr').find('.mrp_price').val(mrpPrice);
        $.ajax({
            url: "{{ route(currentUser().'.do_data_get') }}",
            type: "GET",
            dataType: "json",
            data: { product_id: product_id },
            success: function (dodata) {
                console.log(dodata);
                let selectElement = $(e).closest('tr').find('.referance_number');
                let dodetailIdInput = $(e).closest('tr').find('.dodetail_id');

                selectElement.empty(); // Clear previous options

                $.each(dodata, function (index, value) {
                    selectElement.append('<option value="' + value.do_id + '" data-dodetail_id="' + value.dodetail_id + '">' + value.reference_num + '</option>');
                });

                selectElement.on('change', function () {
                    let selectedOption = $(this).find('option:selected');
                    let dodetailId = selectedOption.data('dodetail_id');
                    dodetailIdInput.val(dodetailId);
                });

                selectElement.trigger('change'); // Initialize the input field
                total_calculate();
                getCtnQty(e);
            },
        });
    }



    {{--  function doData(e) {
        let product_id = $(e).closest('tr').find('.product_id').val();
        let cn=$(e).closest('tr').find('.ctn').val()?parseFloat($(e).closest('tr').find('.ctn').val()):0;

        $.ajax({
            url: "{{ route(currentUser().'.do_data_get') }}",
            type: "GET",
            dataType: "json",
            data: { product_id: product_id },
            success: function(dodata) {
                console.log(dodata);
                let selectElement = $(e).closest('tr').find('.referance_number');
                selectElement.empty(); // Clear previous options

                $.each(dodata, function(index, value) {
                    selectElement.append('<option value="' + value + '">' + value + '</option>');
                });
                let dp=$(e).find('option:selected').data('dp');
                $(e).closest('tr').find('.dp').val(dp);
            },
        });
    }  --}}

    function getCtnQty(e){

        let product_id = $(e).closest('tr').find('.product_id').val();
        let cn=$(e).closest('tr').find('.ctn').val()?parseFloat($(e).closest('tr').find('.ctn').val()):0;
        let pcs=$(e).closest('tr').find('.pcs').val()?parseFloat($(e).closest('tr').find('.pcs').val()):0;
        let freePcs=$(e).closest('tr').find('.free_pcs').val()?parseFloat($(e).closest('tr').find('.free_pcs').val()):0;
        let dpPrice=$(e).closest('tr').find('.dp_pcs').val()?parseFloat($(e).closest('tr').find('.dp_pcs').val()):0;
        $(e).closest('tr').find('.receive').val(pcs);
        $.ajax({
            url: "{{route(currentUser().'.unit_data_get')}}",
            type: "GET",
            dataType: "json",
            data: { product_id:product_id },
            success: function(data) {
                console.log(data);
                let dpCtn=parseFloat(dpPrice*data).toFixed(2);
                let total=(cn*data)+pcs;
                totalReceive=(total+freePcs);
                let subTotal=parseFloat(total*dpPrice).toFixed(2);
                $(e).closest('tr').find('.dp').val(dpCtn);
                $(e).closest('tr').find('.subtotal_dp_pcs').val(subTotal);
                $(e).closest('tr').find('.receive').val(totalReceive);
                //let dpPcs=parseFloat(dpPrice/data).toFixed(2);
                //let total=(cn*data)+pcs;
                //totalReceive=(total+freePcs);
                //let subTotal=parseFloat(total*dpPcs).toFixed(2);
                //$(e).closest('tr').find('.dp_pcs').val(dpPcs);
                //$(e).closest('tr').find('.subtotal_dp_pcs').val(subTotal);
                //$(e).closest('tr').find('.receive').val(totalReceive);
               // changeDp(e,total);
               total_calculate();

            },
        });
    }
    {{--  function changeDp(e, total) {
        let cdp = $(e).closest('tr').find('.dp_pcs').val();
        csubtotal = cdp * total;
        $(e).closest('tr').find('.subtotal_dp_pcs').val(csubtotal);
    }  --}}

    total_calculate();
    function total_calculate() {
        var finalTotal = 0;
        $('.subtotal_dp_pcs').each(function() {
            finalTotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        console.log(finalTotal);
        $('.total_dp').text(parseFloat(finalTotal).toFixed(2));
    }
    function removeCharacter(e) {
        newString = e.value.replace(/[^a-zA-Z0-9]/g, '');
        e.value= newString;
    }
</script>

@endpush
