@extends('layout.app')

@section('pageTitle',trans('নতুন বিক্রয়'))
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
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for=""><b>তারিখ</b></label>
                                        <input type="text" id="datepicker" class="form-control" value="<?php print(date("m/d/Y")); ?>"  name="sales_date" placeholder="mm-dd-yyyy">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for=""><b>সেল সেন্টার</b></label>
                                        <select class="form-control form-select shop_id select2" id="shop_id" name="shop_id">
                                            <option value="">Select</option>
                                            @forelse ($shops as $p)
                                            <option value="{{$p->id}}" {{ request('shop_id')==$p->id?"selected":""}}>{{$p->owner_name}}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for=""><b>পন্য নির্বাচন</b></label>
                                        <select class="form-control form-select product_id select2" id="product_id" name="product_id" onchange="getproductData(this);">
                                            <option value="">Select Product</option>
                                            @forelse ($product as $p)
                                            <option value="{{$p->id}}" {{ request('product_id')==$p->id?"selected":""}}>{{$p->product_name}}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_pcs">{{__('পিস')}}</label>
                                        <input type="number" placeholder="পিস" min="0" step="0.01" class="form-control product_pcs" value="{{ old('product_pcs')}}" name="product_pcs">

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="kg">{{__('কেজি')}}</label>
                                        <input type="number" onkeyup="TotalPriceCount(this);" onchange="TotalPriceCount(this);" placeholder="কেজি" min="0" step="0.01" class="form-control kg" value="{{ old('kg')}}" name="kg">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="gm">{{__('গ্রাম')}}</label>
                                        <input type="number" onkeyup="TotalPriceCount(this);" onchange="TotalPriceCount(this);" placeholder="গ্রাম" min="0" step="0.01" class="form-control gm" value="{{ old('gm')}}" name="gm">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_price">{{__('দর')}}</label>
                                        <input type="number" onkeyup="TotalPriceCount(this);" onchange="TotalPriceCount(this);" placeholder="দর" min="0" step="0.01" class="form-control product_price" value="{{ old('product_price')}}" name="product_price">

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="total_taka">{{__('মোট টাকা')}}</label>
                                        <input type="number" placeholder="মোট টাকা" min="0" step="0.01" class="form-control total_taka" value="{{ old('total_taka')}}" name="total_taka">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
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
function getproductData(e) {
    let product_id = $('.product_id').val();

    $.ajax({
        url: "{{ route(currentUser().'.getproduct') }}", // Get product details
        type: "GET",
        dataType: "json",
        data: { productId: product_id },
        success: function(response) {
            let productPrice = parseFloat(response) || 0; // Ensure it's a valid number
            $('.product_price').val(productPrice);

            // Call TotalPriceCount after setting the new price
            TotalPriceCount();
        },
    });
}

function TotalPriceCount() {
    let kg = parseFloat($('.kg').val()) || 0; // Default to 0 if empty
    let gm = parseFloat($('.gm').val()) || 0;
    let product_price = parseFloat($('.product_price').val()) || 0;

    let gmTk = (gm * product_price) / 1000; // Convert grams to kg price
    let totalprice = (product_price * kg) + gmTk;

    $('.total_taka').val(totalprice.toFixed(2)); // Ensure two decimal places
}

// Recalculate total price when kg, gm, or product price changes
$(document).on("keyup change", ".kg, .gm, .product_price", function() {
    TotalPriceCount();
});

// Ensure total price updates when product changes
$(document).on("change", ".product_id", function() {
    getproductData(this);
});

</script>
<script>
    function total_calculate() {
        var subtotal = 0;
        $('.subtotal_price').each(function() {
            subtotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        $('.total').text(parseFloat(subtotal).toFixed(2));
        $('.total_p').val(parseFloat(subtotal).toFixed(2));

    }


</script>


@endpush
