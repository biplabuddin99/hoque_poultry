@extends('layout.app')
@section('pageTitle','Shop Due List')
@section('pageSubTitle','List')

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-12">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1 d-none">
                                    <select class="select2 form-select shop_id" name="shop_id">
                                        <option value="">select shop</option>
                                        @foreach (\App\Models\Settings\Shop::select('id','shop_name','owner_name')->get() as $shop)
                                        <option value="{{ $shop->id }}" {{ request('shop_id')==$shop->id?"selected":""}}>{{ $shop->shop_name }}({{ $shop->owner_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                                    <input type="text" class="form-control" value="{{ request('shop_name')}}" name="shop_name" placeholder="shop name type">
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                                    <input type="text" class="form-control" value="{{ request('memu_no')}}" name="memu_no" placeholder="memu type here">
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                                    <select class="select2 form-select" name="sr_id">
                                        <option value="">Select SR</option>
                                        @foreach ($srUsers as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                                    <select class="select2 form-select" name="collection_by">
                                        <option value="">Collection By</option>
                                        @foreach ($users as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}-({{ $s?->role?->type }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                                    <select class="select2 form-select" name="area">
                                        <option value="">Select area</option>
                                        @foreach ($area as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 py-1">
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
                            <div class="row mt-4">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <a href="{{ route(currentUser().'.shopbalance.index') }}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <a class="float-end" href="{{route(currentUser().'.shopbalance.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>

                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col" width="7%">{{__('Sales Date')}}</th>
                                <th scope="col">{{__('Distributor')}}</th>
                                <th scope="col">{{__('Sales By')}}</th>
                                <th scope="col">{{__('Sales DSR')}}</th>
                                <th scope="col">{{__('Collection By')}}</th>
                                <th scope="col" width="7%">{{__('Collection date')}}</th>
                                <th scope="col" width="7%">{{__('Check date')}}</th>
                                <th scope="col">{{__('Shop Name')}}</th>
                                <th scope="col" width="9%">{{__('Memu No')}}</th>
                                <th scope="col">{{__('Total Tk')}}</th>
                                <th class="white-space-nowrap"><span class="text-danger"><i class="bi bi-currency-dollar" style="font-size:1rem; color:rgb(246, 50, 35);"></i></span></th> 
                                <th scope="col">{{__('Pay')}}</th>
                                <th scope="col">{{__('Owner Name')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $shopbalance=0;
                                $shopPaybalance=0;
                                $sl=1;
                            @endphp
                            @forelse($shops as $data)
                            {{-- @php $shopbalance=$data->where('status',0)->sum('balance_amount') - $data->where('status',1)->sum('balance_amount') @endphp --}}
                            @if ($data->balance_amount > ($data->collect_amount + $data->check_collect_amount))
                                <tr>
                                    <th scope="row">{{ $sl++ }}</th>
                                    <td>
                                        {{ $data->sales_date?->sales_date ? \Carbon\Carbon::parse($data->sales_date?->sales_date)->format('d-m-Y') : '' }}
                                    </td>
                                    <td>{{$data->shop?->distributor?->name}}</td>
                                    <td>
                                        @if ($data->sr_id != '')
                                        {{$data?->user?->name}} ({{ $data?->user?->role?->type }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ $data->sales_date?->dsr?->name }}
                                    </td>
                                    <td>
                                        @if ($data->collection_by != '')
                                        {{$data?->collectionUser?->name}} ({{ $data?->collectionUser?->role?->type }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ $data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : '' }}
                                    </td>
                                    <td>
                                        {{ $data->check_date ? \Carbon\Carbon::parse($data->check_date)->format('d-m-Y') : '' }}
                                    </td>
                                    <td>
                                        <a href="{{route(currentUser().'.shop_balance_history',$data->shop_id)}}">{{$data->shop?->shop_name}}</a>
                                    </td>
                                    <td>{{$data->reference_number}}</td>
                                    <td class="text-end">
                                        {{ money_format($data->balance_amount - ($data->collect_amount + $data->check_collect_amount)) }}
                                        @php
                                            $shopbalance += $data->balance_amount - ($data->collect_amount + $data->check_collect_amount);
                                        @endphp
                                    </td>
                                    <td class="text-center white-space-nowrap">
                                        @if ($data->status==0)
                                            <button class="btn p-0 m-0" type="button" style="background-color: none; border:none;"
                                                data-bs-toggle="modal" data-bs-target="#checkList"
                                                data-check-id="{{$data->id}}"
                                                data-shop-name="{{$data->shop?->shop_name}}"
                                                data-shop-amount="{{$data->balance_amount - ($data->collect_amount + $data->check_collect_amount)}}"
                                                data-collection-id="{{$data->collection_by}}">
                                                <span class="text-danger"><i class="bi bi-currency-dollar" style="font-size:1rem; color:rgb(246, 50, 35);"></i></span>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($data->collect_amount != 0)
                                            {{ money_format($data->collect_amount) }}
                                            @php
                                                $shopPaybalance += $data->collect_amount;
                                            @endphp
                                        @endif
                                    </td>
                                    {{-- <td class="text-end"> @if($data->status==0)
                                            <span class="me-3"></span>{{ money_format($data->balance_amount - $data->collect_amount) }}
                                            
                                        @elseif($data->status==1)
                                            <span class="me-3">pay-</span>{{ money_format($data->balance_amount) }}
                                        @endif
                                    </td> --}}
                                    
                                    <td>{{$data->shop?->owner_name}}</td>
                                </tr>
                            @endif
                            @empty
                            <tr>
                                <th colspan="13" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="10" class="text-end">Total</th>
                                <th class="text-end"> <span class="me-5"></span>
                                    {{ money_format($shopbalance) }}
                                </th>
                                <th></th>
                                <th class="text-end"> <span class="me-5"></span>
                                    {{ money_format($shopPaybalance) }}
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="my-3">
                    {!! $shops->withQueryString()->links()!!}
               </div>
               <div class="modal fade" id="checkList" tabindex="-1" role="dialog"
                    aria-labelledby="balanceTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <form method="post" id="checkUpdate"  action="{{route(currentUser().'.collection_by_update')}}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header py-1">
                                    <h5 class="modal-title" id="batchTitle">Collection By</h5>
                                    <button type="button" class="close text-danger" data-bs-dismiss="modal"  aria-label="Close">
                                        <i class="bi bi-x-lg" style="font-size: 1.5rem;"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <input type="hidden" id="check_id" name="dueId" value="">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th>Shop Name</th>
                                                <td id="name"></td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Due Amount</th>
                                                <td id="totalAmount"></td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Cash Type</th>
                                                <td>
                                                    <select name="cash_type" class="form-control form-select" onchange="cashTypeChange(this);">
                                                        <option value="0">Cash</option>
                                                        <option value="1">Check</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="bg-light check_number_tr d-none">
                                                <th>Check Number</th>
                                                <td>
                                                    <input type="text" class="form-control" name="check_number">
                                                </td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Receive Amount</th>
                                                <td>
                                                    <input type="number" onkeyup="receiveCondition(this);" class="form-control" name="collect_amount">
                                                    <input type="hidden" id="totalDueAmount" class="form-control totalDueAmount">
                                                    <span class="error-message text-danger"></span>
                                                </td>
                                            </tr>
                                            <tr class="bg-light collection_date_tr d-none">
                                                <th>Receive Date</th>
                                                <td>
                                                    <input type="date" class="form-control" name="collection_date">
                                                </td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Collection By</th>
                                                <td width="50%">
                                                    <select class="select2 form-select" name="user_id" id="collection-id">
                                                        <option value="">Select</option>
                                                        @foreach ($users as $s)
                                                        <option value="{{ $s->id }}">{{ $s->name }} - {{$s->contact_no}} ({{ $s?->role?->type }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
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
    function cashTypeChange(e){
        let cashType = $(e).val();
        if(cashType == 1){
            $(e).closest('tr').next('.check_number_tr').removeClass('d-none');
            $(e).closest('tr').nextAll('.collection_date_tr').first().removeClass('d-none');
        } else {
            $(e).closest('tr').next('.check_number_tr').addClass('d-none');
            $(e).closest('tr').nextAll('.collection_date_tr').first().addClass('d-none');
        }
    }

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