@extends('layout.app')
@section('pageTitle','All Collection List')
@section('pageSubTitle','List')

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-10">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-3 d-none">
                                    <select class="select2 form-select shop_id" name="shop_id">
                                        <option value="">select shop</option>
                                        @foreach (\App\Models\Settings\Shop::select('id','shop_name','owner_name')->get() as $shop)
                                        <option value="{{ $shop->id }}" {{ request('shop_id')==$shop->id?"selected":""}}>{{ $shop->shop_name }}({{ $shop->owner_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <input type="text" class="form-control" value="{{ request('shop_name')}}" name="shop_name" placeholder="shop name type">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <select class="select2 form-select" name="collection_by">
                                        <option value="">Collection By</option>
                                        @foreach ($users as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}-({{ $s?->role?->type }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <div class="input-group">
                                        <select name="distributor" class="select1 form-select shop_id">
                                            <option value="">{{ __('Select Distributor') }}</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request()->get('distributor') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                                    <select class="select2 form-select" name="area">
                                        <option value="">Select area</option>
                                        @foreach ($area as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 py-1">
                                    <label for="fdate">{{__('From Date')}}</label>
                                    <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 py-1">
                                    <label for="fdate">{{__('To Date')}}</label>
                                    <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                                </div>
                                {{-- <div class="col-lg-4 col-md-6 col-sm-12 py-1">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ old('start_date',$startDate) }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 py-1">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date',$endDate) }}" required>
                                </div> --}}
                                <div class="col-lg-6 ps-0">
                                    <button class="btn btn-sm btn-info float-end" type="submit">Search</button>
                                </div>
                                <div class="col-lg-6 p-0 m-0">
                                    <a class="btn btn-sm btn-warning ms-2" href="{{route(currentUser().'.collect_index')}}" title="Clear">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        {{-- <a class="float-end" href="{{route(currentUser().'.shopbalance.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a> --}}
                    </div>
                </div>

                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="text-center">
                                {{-- <th scope="col">{{__('#SL')}}</th> --}}
                                <th scope="col">{{__('Sales By')}}</th>
                                <th scope="col">{{__('Collection By')}}</th>
                                <th scope="col">{{__('Shop Name')}}</th>
                                <th scope="col">{{__('Distributor')}}</th>
                                <th scope="col">{{__('Owner Name')}}</th>
                                <th scope="col">{{__('Pay')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $shopbalance=0;
                                $shopPaybalance=0;
                                $sl=1;
                            @endphp
                            @forelse($shops as $data)
                            @if ($data->cash_type == 0 && $data->check_type != 1)
                                <tr>
                                    <td>
                                        @if ($data->sr_id != '')
                                        {{$data?->user?->name}} ({{ $data?->user?->role?->type }})
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->collection_by != '')
                                        {{$data?->collectionUser?->name}} ({{ $data?->collectionUser?->role?->type }})
                                        @endif
                                    </td>
                                    <td>{{$data->shop?->shop_name}}</td>
                                    <td>{{$data->shop?->distributor?->name}}</td>
                                    <td>{{$data->shop?->owner_name}}</td>
                                    <td class="text-end">
                                        {{ money_format($data->balance_amount) }}
                                        @php
                                            $shopPaybalance += $data->balance_amount;
                                        @endphp
                                    </td>
                                </tr>
                            @endif
                            @foreach ($data?->shopCollection as $data)
                                <tr>
                                    <td>
                                        {{-- @if ($data->sr_id != '')
                                        {{$data?->user?->name}} ({{ $data?->user?->role?->type }})
                                        @endif --}}
                                    </td>
                                    <td>
                                        @if ($data->collection_by != '')
                                        {{$data?->collectionUser?->name}} ({{ $data?->collectionUser?->role?->type }})
                                        @endif
                                    </td>
                                    <td>{{$data->shop?->shop_name}}</td>
                                    <td>{{$data->shop?->distributor?->name}}</td>
                                    <td>{{$data->shop?->owner_name}}</td>
                                    <td class="text-end">
                                        {{ money_format($data->collect_amount) }}
                                        @php
                                            $shopPaybalance += $data->collect_amount;
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach
                            @empty
                            <tr>
                                <th colspan="6" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th class="text-end"> <span class="me-5"></span>
                                    {{ money_format($shopbalance + $shopPaybalance) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="my-3">
                    {!! $shops->withQueryString()->links()!!}
               </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#checkList').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var checkId = button.data('check-id');
            var shopName = button.data('shop-name');
            var shopAmount = button.data('shop-amount');
            var collectId = button.data('collection-id');
            // Set the values in the modal
            var modal = $(this);
            modal.find('#check_id').val(checkId);
            modal.find('#name').text(shopName);
            modal.find('#totalAmount').text(shopAmount);
            modal.find('#totalDueAmount').val(shopAmount);
            modal.find('#collection-id').val(collectId);
        });
    });
    $('#checkList').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#checkList')
        });
    });
    function receiveCondition(e){
        let dueAmount = $(e).closest('tr').find('.totalDueAmount').val()?parseFloat($(e).closest('tr').find('.totalDueAmount').val()):0;
        let receiveAmount = $(e).val();
        let errorMessage = $(e).closest('td').find('.error-message');
        if (receiveAmount > dueAmount) {
            errorMessage.text("You can't receive more than due amount");
            $(e).val(dueAmount);
        } else {
            errorMessage.text("");
        }
    }
</script>
@endpush