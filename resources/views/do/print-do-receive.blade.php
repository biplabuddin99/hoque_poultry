@extends('layout.app')

@section('pageTitle',trans('Receive Do'))
@section('pageSubTitle',trans('Receive'))

@section('content')
@php $settings=App\Models\Settings\Company::first(); @endphp
<section id="result_show">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row text-center">
                            <h3> {{ $settings->name }}</h3>
                            <p class="mb-1">Contact: {{ $settings->contact }}</p>
                            <p>Address :{{ $settings->address }}</p>
                        </div>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 15%"><b>Stock Date</b></td>
                                <td style="width: 2%">:</td>
                                <td style="width: 83%">{{ $stockDate }}</td>
                            </tr>
                            <tr>
                                <td style="width: 15%"><b>Chalan NO</b></td>
                                <td style="width: 2%">:</td>
                                <td style="width: 83%">{{ $chalanNo }}</td>
                            </tr>
                        </table>
                        <!-- table bordered -->
                        <div class="row p-2 mt-4">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col">{{__('Product Name')}}</th>
                                            {{--  <th scope="col">{{__('Lot Number')}}</th>  --}}
                                            <th scope="col">{{__('Do Referance')}}</th>
                                            <th scope="col">{{__('CTN')}}</th>
                                            <th scope="col">{{__('PCS')}}</th>
                                            <th scope="col">{{__('Free')}}</th>
                                            <th scope="col">{{__('Receive(PCS)')}}</th>
                                            <th scope="col">{{__('Dp(CTN)')}}</th>
                                            <th scope="col">{{__('Dp(PCS)')}}</th>
                                            <th scope="col">{{__('SubTotal-Dp')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product">
                                        @foreach ($print_data as $data)
                                        <tr class="text-center">
                                            <td>{{ $data->product?->product_name }}</td>
                                            <td>{{ $data->do?->reference_num }}</td>
                                            <td>{{ $data->ctn }}</td>
                                            <td>{{ $data->pcs }}</td>
                                            <td>{{ $data->quantity_free }}</td>
                                            <td>{{ $data->totalquantity_pcs }}</td>
                                            <td>{{ $data->dp_price }}</td>
                                            <td>{{ $data->dp_pcs }}</td>
                                            <td>{{ $data->subtotal_dp_pcs }}
                                                <input type="hidden" value="{{ $data->subtotal_dp_pcs }}" class="final_total">
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="8" class="text-end">Total</th>
                                            <th class="text-center">
                                                <span class="total_dp"></span>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
@endsection
@push("scripts")
<script>
    total_calculate();
    function total_calculate() {
        var finalTotal = 0;
        $('.final_total').each(function() {
            finalTotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
        });
        $('.total_dp').text(parseFloat(finalTotal).toFixed(2));
    }
</script>
@endpush
{{--  @push('scripts')
<script>
    function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
@endpush  --}}
