@extends('layout.app')
@section('pageTitle', trans('Product List'))
@section('pageSubTitle', trans('List'))

@section('content')
<style>
    input{
        width: 100px;
    }
</style>
<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-10">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-4 py-1">
                                    <label for="distributor">{{ __('Distributor') }}</label>
                                    <select name="distributor_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($distributors as $d)
                                            <option value="{{ $d->id }}" {{ request('distributor_id') == $d->id ? "selected" : "" }}>{{ $d->name }}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-4 py-1 mt-4">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a class="btn btn-sm btn-warning" href="{{ route(currentUser().'.product_price') }}" title="Clear">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2"></div>
                </div>
                <!-- Table bordered -->
                <form action="{{ route('product_price_update') }}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col">{{ __('#SL') }}</th>
                                    <th scope="col">{{ __('Group') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Distributor') }}</th>
                                    <th scope="col">{{ __('Unit Style') }}</th>
                                    <th scope="col">{{ __('Free Ratio') }}</th>
                                    <th scope="col">{{ __('Free (PCS)') }}</th>
                                    <th scope="col">{{ __('DP Price') }}</th>
                                    <th scope="col">{{ __('TP Price') }}</th>
                                    <th scope="col">{{ __('TP Free') }}</th>
                                    <th scope="col">{{ __('MRP Price') }}</th>
                                    <th scope="col">{{ __('Free Taka') }}</th>
                                    <th scope="col">{{ __('Adjust') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product as $key => $p)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $p->group?->name }}</td>
                                    <td>{{ $p->product_name }}</td>
                                    <td>{{ $p->distributor?->name }}</td>
                                    <td>
                                        {{-- <input type="hidden" name="unit_style_id[{{ $p->id }}]" class="unit_style_id" value="{{ $p->unit_style_id }}">
                                        {{ $p->unit_style?->name }} --}}
                                        {{-- <input type="text"  value="{{ $p->unit_style?->name }}"> --}}
                                        <select name="unit_style_id[{{ $p->id }}]" class="unit_style_id" required style="width: 100px;">
                                            <option value="">Select</option>
                                            @forelse($unit_style as $d)
                                                <option value="{{$d->id}}" {{ $p->unit_style_id==$d->id?"selected":""}}> {{ $d->name}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="free_ratio[{{ $p->id }}]" onkeyup="tpFree(this, {{ $p->id }})" class="free_ratio" value="{{ $p->free_ratio }}">
                                    </td>
                                    <td>
                                        <input type="text" name="free_pcs[{{ $p->id }}]" min="0" step="0.01" onkeyup="tpFree(this, {{ $p->id }})" class="free_pcs" value="{{ $p->free }}"> 
                                    </td>
                                    <td>
                                        <input type="text" name="dp_price[{{ $p->id }}]" value="{{ $p->dp_price }}">
                                    </td>
                                    <td>
                                        <input type="text" name="tp_price[{{ $p->id }}]" min="0" step="0.01" onkeyup="tpFree(this, {{ $p->id }})" class="tp_price" value="{{ $p->tp_price }}">
                                    </td>
                                    <td>
                                        <input type="text" name="tp_free[{{ $p->id }}]" class="tp_free_{{ $p->id }}" value="{{ $p->tp_free }}" readonly>
                                        <input type="hidden" class="tp_free_up" value="{{ $p->tp_free - $p->adjust }}" name="tp_free_up[{{ $p->id }}]">
                                    </td>
                                    <td>
                                        <input type="text" name="mrp_price[{{ $p->id }}]" value="{{ $p->mrp_price }}">
                                    </td>
                                    <td>
                                        <input type="text" name="free_taka[{{ $p->id }}]" value="{{ $p->free_taka }}">
                                    </td>
                                    <td>
                                        <input type="text" name="adjust[{{ $p->id }}]" onkeyup="Adjust(this, {{ $p->id }})" class="adjust" value="{{ $p->adjust }}">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="13" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-info me-1 mb-1">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Bordered table end -->

<script>

    function tpFree(e, productId){
        //let unitStyleId = $('input[name="unit_style_id[' + productId + ']"]').val();
        let unitStyleId = $('select[name="unit_style_id[' + productId + ']"]').val();
        let freeRatio = parseFloat($('input[name="free_ratio[' + productId + ']"]').val()) || 0;
        let freePcs = parseFloat($('input[name="free_pcs[' + productId + ']"]').val()) || 0;
        let tpPrice = parseFloat($('input[name="tp_price[' + productId + ']"]').val()) || 0;
        
        $.ajax({
            url: "{{ route(currentUser().'.unit_pcs_get') }}",
            type: "GET",
            dataType: "json",
            data: { unit_style_id: unitStyleId },
            success: function(data) {
                if(tpPrice && data){
                    let total = parseFloat((data / freeRatio) * freePcs).toFixed(2);
                    let tpfreeCal = parseFloat(total) + parseFloat(data);
                    let tpfree = (tpPrice * data) / tpfreeCal || 0;
                    $('input[name="tp_free[' + productId + ']"]').val(parseFloat(tpfree).toFixed(2));
                    $('input[name="tp_free_up[' + productId + ']"]').val(tpfree);
                    Adjust(e, productId);
                }
            },
        });
    }

    function Adjust(e, productId){
        let adjust = parseFloat($('input[name="adjust[' + productId + ']"]').val()) || 0;
        let tpFreeValue = parseFloat($('input[name="tp_free_up[' + productId + ']"]').val()) || 0;
        let tpFreeUp = (adjust + tpFreeValue).toFixed(2);
        $('input[name="tp_free[' + productId + ']"]').val(tpFreeUp);
    }
</script>
@endsection
