@extends('layout.app')

@section('pageTitle',trans('Update Product'))
@section('pageSubTitle',trans('Update'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.product.update',encryptor('encrypt',$product->id))}}">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="" for="cat">{{__('Distributor')}}</label>
                                            <select class="choices form-select distributor_id" name="distributor_id">
                                                 <option value="">Select Distributor</option>
                                                @forelse (App\Models\Settings\Supplier::where(company())->get() as $sup)
                                                    <option value="{{ $sup->id }}" {{ $product->distributor_id==$sup->id?'selected':'' }}>{{ $sup->name }}</option>
                                                @empty
                                                    <option value="">No Data Found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="product_name">{{__('পণ্যের নাম')}}</label>
                                            <input type="text" placeholder="পণ্যের নাম" class="form-control" value="{{ old('product_name',$product->product_name)}}" name="product_name">
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="group_id">{{__('Group')}}<span class="text-danger">*</span></label>
                                            <select required name="group_id" class="select2 form-control form-select" >
                                                <option value="">Select</option>
                                                @forelse($group as $d)
                                                    <option value="{{$d->id}}" {{ $product->group_id==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="unit_style_id">{{__('Unit Style')}}</label>
                                            <select onchange="tpFree(this)" name="unit_style_id" class="select2 form-control form-select unit_style_id" required>
                                                <option value="">Select</option>
                                                @forelse($unit_style as $d)
                                                    <option value="{{$d->id}}" {{ $product->unit_style_id==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="free_ratio">{{__('Free Ratio')}}</label>
                                            <input type="number" min="0" onkeyup="tpFree(this)" class="form-control free_ratio" value="{{ old('free_ratio',$product->free_ratio)}}" name="free_ratio">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="free">{{__('Free(PCS)')}}</label>
                                            <input type="number" min="0" step="0.01" onkeyup="tpFree(this)" class="form-control free_pcs" value="{{ old('free',$product->free)}}" name="free">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="dp_price">{{__('DP Price')}}</label>
                                            <input type="number" min="0" step="0.01" class="form-control" value="{{ old('dp_price',$product->dp_price)}}" name="dp_price">

                                        </div>
                                    </div> --}}
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tp_price">{{__('দর')}}</label>
                                            <input type="number" min="0" step="0.01" class="form-control tp_price" value="{{ old('tp_price',$product->tp_price)}}" name="tp_price" placeholder="দর">
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tp_price">{{__('Price')}}</label>
                                            <input type="number" min="0" step="0.01" onkeyup="tpFree(this)" class="form-control tp_price" value="{{ old('tp_price',$product->tp_price)}}" name="tp_price">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tp_free">{{__('TP Free')}}</label>
                                            <input type="text"  class="form-control tp_free" value="{{ old('tp_free',$product->tp_free)}}" name="tp_free">
                                            <input type="hidden" class="form-control tp_free_up" value="{{$product->tp_free - $product->adjust}}" name="">
                                            <span class="tp_free_span mt-3" style="display: none">{{ $product->tp_free }}</span>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="mrp_price">{{__('MRP Price')}}</label>
                                            <input type="number" class="form-control" value="{{ old('mrp_price',$product->mrp_price)}}" name="mrp_price">

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="free_taka">{{__('Free Taka')}}</label>
                                            <input type="number" class="form-control" value="{{ old('free_taka',$product->free_taka)}}" name="free_taka">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="adjust">{{__('Adjust')}}</label>
                                            <input type="text" onkeyup="Adjust(this)" class="form-control adjust" value="{{ old('adjust',$product->adjust)}}" name="adjust">
                                        </div>
                                    </div> --}}
                                    {{--  <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="base_unit">{{__('Unit')}}</label>
                                            <select name="base_unit" class="form-control form-select" >
                                                <option value="">Select</option>
                                                @forelse($base_unit as $d)
                                                    <option class="unit unit{{$d->unit_style_id}}" value="{{$d->id}}" {{ $product->base_unit==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>  --}}
                                    {{--  <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="color">{{__('Color')}}</label>
                                            <input type="text" class="form-control" value="{{ old('color',$product->color)}}" name="color">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="size">{{__('Size')}}</label>
                                            <input type="text" class="form-control" value="{{ old('size',$product->size)}}" name="size">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="weight">{{__('Weight')}}</label>
                                            <input type="text" class="form-control" value="{{ old('weight',$product->weight)}}" name="weight">
                                        </div>
                                    </div>  --}}
                                    {{--  <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="image">{{__('Image')}}</label>
                                            <input type="file" class="form-control" value="{{ old('image')}}" name="image">

                                        </div>
                                    </div>  --}}
                                    {{--  <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Status')}}</label>
                                            <select name="status" class="form-control form-select">
                                                <option value="">select</option>
                                                <option value="0" {{ $product->status==0?"selected":""}}>Inactive</option>
                                                <option value="1" {{ $product->status==1?"selected":""}}>Active</option>
                                            </select>
                                        </div>
                                    </div>  --}}

                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-info me-1 mb-1">Update</button>

                                    </div>
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
<script>
    /* call on load page */
    $(document).ready(function(){
        $('.unit').hide();
    });

    function show_unit(e){
        $('.unit').hide();
        $('.unit'+e).show()
    }
</script>
<script>
    function tpFree(e){
        let unitStyleId=$('.unit_style_id').find(":selected").val();
        let freeRatio=parseFloat($('.free_ratio').val());
        let freePcs=parseFloat($('.free_pcs').val());
        let tpPrice=parseFloat($('.tp_price').val())?parseFloat($('.tp_price').val()):0;;
        //console.log(tpPrice)
        $.ajax({
            url: "{{route(currentUser().'.unit_pcs_get')}}",
            type: "GET",
            dataType: "json",
            data: { unit_style_id:unitStyleId },
            success: function(data) {
            //console.log(data);
                if(tpPrice){
                    let total=(parseFloat((data/freeRatio)*freePcs).toFixed(2));
                    let tpfreeCal=(parseFloat(total)+parseFloat(data));
                    let tpfree=(parseFloat(tpPrice*data)/parseFloat(tpfreeCal))?(parseFloat(tpPrice*data)/parseFloat(tpfreeCal)):0;
                    $('.tp_free').val(parseFloat(tpfree).toFixed(2));
                    $('.tp_free_up').val(tpfree);
                    Adjust(e);
                }

            },
        });
    }
    function Adjust(e){
        let adjust=$('.adjust').val();
        let tpFreeValue=$('.tp_free_up').val();
        let tpFreeUp=(parseFloat(adjust)+parseFloat(tpFreeValue)).toFixed(2);
        console.log(adjust)
        console.log(tpFreeUp)
        $('.tp_free').val(tpFreeUp);
    }
</script>
<script>
    $(document).ready(function(){
        $('.tp_free').on('click', function() {
            $(this).siblings('.tp_free_span').show();
        });

        $('.tp_free').on('blur', function() {
            $(this).siblings('.tp_free_span').hide();
        });
    });
</script>
@endpush
