@extends('layout.app')

@section('pageTitle',trans('Receive From Return'))
@section('pageSubTitle',trans('receive'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.receive_rp',encryptor('encrypt',$return->id))}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12 d-none">
                                    <div class="form-group">
                                        <label for="garir_number">{{__('Garir Number')}}</label>
                                        <input type="text" class="form-control" value="{{ old('garir_number',$return->garir_number)}}" name="garir_number">

                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="invoice_number">{{__('Chalan No')}}</label>
                                        <input type="text" class="form-control" value="{{ old('invoice_number',$return->invoice_number)}}" name="invoice_number" readonly>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="note">Distributor</label>
                                        <input type="text" class="form-control" value="{{$return->distributor?->name}}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 d-none">
                                    <div class="form-group">
                                        <label for="distributor">Distributor</label>
                                        <select name="distributor_id" class="form-control form-select supplier_id" onchange="getDistProduct('product_id','group_id')" required>
                                            <option value="">select distributor</option>
                                            @foreach ($distributor as $d)
                                                <option value="{{$d->id}}" {{$return->distributor_id == $d->id? 'selected' : ''}}>{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 d-none">
                                    <div class="form-group">
                                        <label for="">Return Type</label>
                                        <select name="return_type" class="form-control form-select" required>
                                            <option value="">select</option>
                                            <option value="0" {{$return->return_type == 0? 'selected' : ''}}>Damage</option>
                                            <option value="1" {{$return->return_type == 1? 'selected' : ''}}>Airless</option>
                                            <option value="2" {{$return->return_type == 2? 'selected' : ''}}>Short Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Receive Type</label>
                                        <select name="receive_type" class="form-control form-select" required>
                                            <option value="">select</option>
                                            <option value="1">Partial Receive</option>
                                            <option value="2">Final Receive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center bg-secondary text-white">
                                            <th width="11%">Group</th>
                                            <th width="22%">Product</th>
                                            {{-- <th width="9%">Return type</th> --}}
                                            <th>CTN</th>
                                            <th>PCS</th>
                                            <th>Total Receive</th>
                                            <th>DP Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="retn_repeat">
                                        @forelse ($returnDetails as $rd)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="group_id[]" value="{{$rd->group_id}}">
                                                    <input type="text" class="form-control" value="{{$rd->group?->name}}" readonly>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="product_id[]" value="{{$rd->product_id}}">
                                                    <input type="text" class="form-control" value="{{$rd->product?->product_name}}" readonly>
                                                </td>
                                                {{-- <td>
                                                    <select name="return_type[]" class="form-control form-select" required>
                                                        <option value="">select</option>
                                                        <option value="0">Damage</option>
                                                        <option value="1">Normal</option>
                                                        <option value="2">Manu fault</option>
                                                    </select>
                                                </td> --}}
                                                <td><input onkeyup="getSubtotal(this);" type="number" name="ctn_return[]" value="{{$rd->ctn_return}}" class="form-control ctn_return"></td>
                                                <td><input onkeyup="getSubtotal(this);" type="number" name="pcs_return[]" value="{{$rd->pcs_return}}" class="form-control pcs_return"></td>
                                                <td>
                                                    <input type="hidden" class="product_unit" value="{{$rd->product?->unit_style?->unit?->qty}}">
                                                    <input type="hidden" value="{{$rd->id}}" name="return_detail_id[]">
                                                    <input type="number" name="total_pcs_return[]" value="{{$rd->total_pcs_return}}" class="form-control total_pcs_return" readonly>
                                                </td>
                                                <td><input onkeyup="getSubtotal(this);" type="text" value="{{$rd->price}}" name="dp_price[]" class="form-control dp_price"></td>
                                                <td><input type="text" name="subtotal_price[]" value="{{$rd->amount}}" class="form-control text-end subtotal_price" readonly></td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Total</th>
                                            <th><input type="text" name="grand_total" class="form-control text-end grand_total_amount" value="{{$return->total}}" readonly></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-12 text-end">
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
@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-element-select.js') }}"></script>

<script>
    function groupWiseProductShow(e){
        let group_id= $(e).val();
        $(e).closest('tr').find('.selecet_p_hide').hide();
        $(e).closest('tr').find('.selecet_p_hide'+group_id).show();
    }

    let old_supplier_id=0;
    let opt="";
    let optGroup="";
    function getDistProduct(product_id,group_id) {
        let supplier_id=$('.supplier_id').val();
        
        if(old_supplier_id!=supplier_id){
            $('.old_tr_remove').closest('tr').remove();
            $('#product_id').closest('tr').find('.ctn_return').val('');
            $('#product_id').closest('tr').find('.pcs_return').val('');
            $('#product_id').closest('tr').find('.total_pcs_return').val('');
            $('#product_id').closest('tr').find('.dp_price').val('');
            $('#product_id').closest('tr').find('.subtotal_price').val('');
            $('.grand_total_amount').val('0');
            $.ajax({
                url: "{{ route(currentUser().'.get_return_product') }}",
                type: "GET",
                dataType: "json",
                data:{supplier_id:supplier_id},
                success: function(datas) {
                    console.log(datas);
                    opt=`<option value="">Select Product</option>`;
                    optGroup=`<option value="">Select Group</option>`;
                    if(datas.data.length > 0){
                        for(d of datas.data){
                            opt+=`<option class="selecet_p_hide selecet_p_hide${d.group_id}" data-dp-price='${d.dp_price}' value="${d.id}">${d.product_name}</option>`;
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
    function addRow(){
        var row=`
        <tr>
            <td>
                <select name="group_id[]" class="select2 old_tr_remove form-control form-select return_group_id" id="group_id_${counter}" onchange="groupWiseProductShow(this);">
                    <option value="">Select Group</option>
                    @foreach ($group as $g)
                        <option value="{{$g->id}}">{{$g->name}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="product_id[]" class="form-control form-select return_product_id" id="product_id_${counter}" onchange="productData(this);">
                    <option value="">Select Product</option>
                    @foreach ($product as $p)
                        <option class="selecet_p_hide selecet_p_hide{{$p->group_id}}" data-dp-price='{{$p->dp_price}}' value="{{$p->id}}">{{$p->product_name}}</option>
                    @endforeach
                </select>
            </td>
           {{-- <td>
                <select name="return_type[]" class="form-control form-select" required>
                    <option value="">select</option>
                    <option value="0">Damage</option>
                    <option value="1">Normal</option>
                    <option value="2">Manu fault</option>
                </select>
            </td> --}}
            <td><input onkeyup="getSubtotal(this);" type="number" name="ctn_return[]" class="form-control ctn_return"></td>
            <td><input onkeyup="getSubtotal(this);" type="number" name="pcs_return[]" class="form-control pcs_return"></td>
            <td>
                <input type="hidden" class="product_unit">
                <input type="number" name="total_pcs_return[]" class="form-control total_pcs_return" readonly>
            </td>
            <td><input onkeyup="getSubtotal(this);" type="text" name="dp_price[]" class="form-control dp_price"></td>
            <td><input type="text" name="subtotal_price[]" class="form-control text-end subtotal_price" readonly></td>
            <td>
                <span onClick='removeRow(this);' class="delete-row text-danger" style="font-size:1rem; margin-left:9px;"><i class="bi bi-trash-fill"></i></span>
            </td>
        </tr>`;
        $('#retn_repeat').append(row);
        
        //getDistProduct('product_id_'+counter,'group_id_'+counter);
        $(`#product_id_${counter}`);
        $(`#group_id_${counter}`).select2();
        // $(`#product_id_${counter}`).select2();
        counter++;
    }
    function removeRow(e){
        if (confirm("Are you sure you want to remove this row?")) {
            $(e).closest('tr').remove();
            total_calculate();
        }
    }

    let old_product_id=0;
    function productData(e) {
        var productId = $(e).closest('tr').find('.return_product_id').val();
        var dpPrice = $(e).closest('tr').find('.return_product_id option:selected').attr('data-dp-price');
        
        if(old_product_id != productId){
            $(e).closest('tr').find('.ctn_return').val('');
            $(e).closest('tr').find('.pcs_return').val('');
            $(e).closest('tr').find('.total_pcs_return').val('');
            $(e).closest('tr').find('.dp_price').val('');
            $(e).closest('tr').find('.subtotal_price').val('');

            $.ajax({
                url: "{{route(currentUser().'.get_return_product_unit')}}",
                type: "GET",
                dataType: "json",
                data: { product_id: productId },
                success: function (data) {
                    // this function return actual quantity unit
                    console.log(data)
                    $(e).closest('tr').find('.product_unit').val(data);
                    $(e).closest('tr').find('.dp_price').val(dpPrice);
                },
                error: function () {
                    console.error("Error fetching data from the server.");
                },
            });
            getSubtotal(e);
            total_calculate();
            old_product_id= productId;
        }
    }
    function getSubtotal(e){
        var ctnQty = $(e).closest('tr').find('.ctn_return').val()? parseFloat($(e).closest('tr').find('.ctn_return').val()) : 0;
        var pcsQty = $(e).closest('tr').find('.pcs_return').val()? parseFloat($(e).closest('tr').find('.pcs_return').val()) : 0;
        var dpPrice = $(e).closest('tr').find('.dp_price').val()? parseFloat($(e).closest('tr').find('.dp_price').val()) : 0;
        var productUnit = $(e).closest('tr').find('.product_unit').val()? parseFloat($(e).closest('tr').find('.product_unit').val()) : 0;
        var totalQty = (ctnQty*productUnit)+pcsQty;
        var subtotal = parseFloat(totalQty*dpPrice).toFixed(2);
        $(e).closest('tr').find('.total_pcs_return').val(totalQty);
        $(e).closest('tr').find('.subtotal_price').val(subtotal);
        total_calculate();
    }
    function total_calculate(){
        var subtotal = 0;
        $('.subtotal_price').each(function() {
            subtotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        console.log(subtotal);
        $('.grand_total_amount').val(parseFloat(subtotal).toFixed(2));
        
    }

</script>

@endpush
