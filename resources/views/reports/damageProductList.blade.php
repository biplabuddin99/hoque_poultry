@extends('layout.app')

@section('pageTitle',trans('Damage Product List'))
@section('pageSubTitle',trans('Reports'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="text-center"><h4>Damage Product List (Report)</h4></div>
                    <div class="card-body">
                        <form class="form" method="get" action="">
                            <div class="row">
                                <div class="col-4 py-1">
                                    <label for="fdate">{{__('From Date')}}</label>
                                    <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                                </div>
                                <div class="col-4 py-1">
                                    <label for="fdate">{{__('To Date')}}</label>
                                    <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                                </div>
                                <div class="col-4 py-1">
                                    <label for="groups">{{__('Group')}}</label>
                                    <select name="group_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($groups as $cat)
                                            <option value="{{$cat->id}}" {{ request('group_id')==$cat->id?"selected":""}}>{{$cat->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-4 py-1 d-none">
                                    <label for="product">{{__('Product Name')}}</label>
                                    <select name="product_id" class="choices form-select">
                                        <option value="">Select</option>
                                        @forelse ($products as $p)
                                            <option value="{{$p->id}}" {{ request('product_id')==$p->id?"selected":""}}>{{$p->product_name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-4 py-1">
                                    <label for="lcNo">{{__('Distributor')}}</label>
                                    <select name="distributor_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($distributors as $d)
                                            <option value="{{$d->id}}" {{ request('distributor_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-4 py-1">
                                    <label for="lcNo">{{__('SR')}}</label>
                                    <select name="sr_id" class="select2 form-select">
                                        <option value="">Select</option>
                                        @forelse ($sr as $d)
                                            <option value="{{$d->id}}" {{ request('sr_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <a href="{{route(currentUser().'.damageProductList')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                                </div>
                            </div>
                            <table class="table mb-5">
                                <thead>
                                    <tr class="bg-primary text-white text-center">
                                        <th class="p-2">{{__('#SL')}}</th>
                                        <th class="p-2" data-title="Product Name">{{__('Group')}}</th>
                                        <th class="p-2" data-title="Product Name">{{__('Distributor')}}</th>
                                        <th class="p-2" data-title="Product Name">{{__('SR')}}</th>
                                        <th class="p-2" data-title="Product Name">{{__('Product')}}</th>
                                        <th class="p-2" data-title="Product Price">{{__('Total Price')}}</th>
                                        <th class="p-2" data-title="Qty(PCS)">{{__('Qty(PCS)')}}</th>
                                        {{--  <th class="p-2" data-title="Total Amount">{{__('Total Amount')}}</th>  --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stock as $s)
                                    <tr class="text-center">
                                        <th scope="row">{{ ++$loop->index }}</th>
                                        <td>{{$s->group_name}}</td>
                                        <td>{{$s->supplier_name}}</td>
                                        <td>
                                            @foreach (App\Models\User::where(company())->where('id',$s->sr)->get() as $sr)
                                                {{$sr->name}}
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{route(currentUser().'.demage.individual',$s->product_id)}}">{{$s->product_name}}</a>
                                        </td>
                                        <td>{{ ($s->totalquantity_pcs )*($s->product_dp) }}

                                            <input type="hidden" class="subtotal_dp_price" value="{{($s->totalquantity_pcs )*($s->product_dp)}}">
                                        </td>
                                        <td>{{ $s->totalquantity_pcs }}</td>
                                        {{--  <td>{{$s->qty}}</td>  --}}
                                    </tr>
                                    @empty
                                    <tr>
                                        <th colspan="9" class="text-center">No data Found</th>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total</th>
                                        <th class="text-center">
                                            <span class="total_dp"></span> Tk
                                        </th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
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
        total_calculate();
        function total_calculate() {
            var finalTotal = 0;
            $('.subtotal_dp_price').each(function() {
                finalTotal+=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val());
            });
            //console.log(finalTotal);
            $('.total_dp').text(parseFloat(finalTotal).toFixed(2));
        }
    </script>

@endpush
