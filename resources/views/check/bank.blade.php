@extends('layout.app')
@section('pageTitle',trans('Bank'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row mb-3">
        <div class="col-4">
            
        </div>
    </div>
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <form action="">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                            <label for="fdate">{{__('From Date')}}</label>
                            <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                            <label for="fdate">{{__('To Date')}}</label>
                            <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="memu">{{__('Memu')}}</label>
                            <input type="text" class="form-control" value="{{ request('memu_no')}}" name="memu_no" placeholder="memu type here">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 py-1">
                            <label for="lcNo">{{__('Distributor')}}</label>
                            <select name="distributor_id" class="select2 form-select">
                                <option value="">Select Distributor</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('distributor') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 py-1">
                            <label for="lcNo">{{__('Collection By')}}</label>
                            <select name="collection_by" class="select2 form-select">
                                <option value="">Select</option>
                                @forelse ($users as $d)
                                    <option value="{{$d->id}}" {{ request('collection_by')==$d->id?"selected":""}}>{{$d->name}}</option>
                                @empty
                                    <option value="">No Data Found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6 d-flex justify-content-end">
                            <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                        </div>
                        <div class="col-6 d-flex justify-content-Start">
                            <a href="{{ route(currentUser().'.check_list_bank') }}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                        </div>
                    </div>
                
                    <div class="table-responsive">
                        @php
                            $totalAmount = 0;   
                        @endphp
                        <table class="table table-bordered mb-0"><thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Shop Name')}}</th>
                                    <th scope="col">{{__('Distributor')}}</th>
                                    <th scope="col">{{__('Sales Date')}}</th>
                                    <th scope="col">{{__('Check Date')}}</th>
                                    <th scope="col">{{__('Memu No')}}</th>
                                    <th scope="col">{{__('Sales DSR')}}</th>
                                    <th scope="col">{{__('Collection By')}}</th>
                                    <th scope="col">{{__('Collection date')}}</th>
                                    <th scope="col">{{__('Amount')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$d->shop?->shop_name}}</td>
                                    <td>{{$d->shop?->distributor?->name}}</td>
                                    <td>
                                        {{ $d->sales_date?->sales_date ? \Carbon\Carbon::parse($d->sales_date?->sales_date)->format('d-m-Y') : '' }}
                                    </td>
                                    <td>
                                        {{ $d->check_date ? \Carbon\Carbon::parse($d->check_date)->format('d-m-Y') : '' }}
                                    </td>
                                    <td>{{$d->reference_number}}</td>
                                    <td>
                                        {{ $d->sales_date?->dsr?->name }}
                                    </td>
                                    <td>
                                        @if ($d->collection_by != '')
                                            {{$d->collectionUser?->name}} ({{ $d?->collectionUser?->role?->type }})
                                        @endif
                                    </td>
                                    <td>
                                        @if ($d->updated_at != '')
                                            {{ $d->updated_at ? \Carbon\Carbon::parse($d->updated_at)->format('d-m-Y') : '' }}
                                        @endif
                                    </td>
                                    <td class="text-end">{{money_format($d->balance_amount-($d->check_collect_amount+$d->collect_amount))}}</td>
                                    <td class="white-space-nowrap">
                                        <button class="btn p-0 m-0" type="button" style="background-color: none; border:none;"
                                            data-bs-toggle="modal" data-bs-target="#checkList"
                                            data-check-id="{{$d->id}}"
                                            data-shop-name="{{$d->shop?->shop_name}}"
                                            data-shop-amount="{{$d->balance_amount-($d->check_collect_amount+$d->collect_amount)}}"
                                            data-collection-id="{{$d->collection_by}}"
                                            <span class="text-danger"><i class="bi bi-currency-dollar" style="font-size:1rem; color:rgb(246, 50, 35);"></i></span>
                                        </button>
                                    </td>
                                </tr>
                                @php
                                    $totalAmount += $d->balance_amount-($d->check_collect_amount+$d->collect_amount);
                                @endphp
                                @empty
                                <tr>
                                    <th colspan="11" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="9" class="text-center">Total</th>
                                    <th class="text-end">{{ money_format($totalAmount)}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
                <div class="modal fade" id="checkList" tabindex="-1" role="dialog"
                    aria-labelledby="balanceTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <form method="post" id="checkUpdate"  action="{{route(currentUser().'.check_list_update')}}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header py-1">
                                    <h5 class="modal-title" id="batchTitle">Chech Status</h5>
                                    <button type="button" class="close text-danger" data-bs-dismiss="modal"  aria-label="Close">
                                        <i class="bi bi-x-lg" style="font-size: 1.5rem;"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <input type="hidden" id="check_id" name="checkId" value="">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th>Shop Name</th>
                                                <td id="name"></td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Amount</th>
                                                <td id="totalAmount"></td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Status</th>
                                                <td>
                                                    <select class="form-select" name="check_type" required>
                                                        <option value="">Select</option>
                                                        <option value="0">Cash</option>
                                                        <option value="2">Bank</option>
                                                        <option value="3">Due</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Collection Date</th>
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
            modal.find('#collection-id').val(collectId);
        });
    });
    $('#checkList').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#checkList')
        });
    });
</script>
@endpush