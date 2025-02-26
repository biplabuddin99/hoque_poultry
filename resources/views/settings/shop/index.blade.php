@extends('layout.app')
@section('pageTitle','সেল সেন্টার তালিকা')
@section('pageSubTitle','List')

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
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="row pb-1">
                    <div class="col-11">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="owner_name" value="{{isset($_GET['owner_name'])?$_GET['owner_name']:''}}" placeholder="মালিক নাম" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="shop_name" value="{{isset($_GET['shop_name'])?$_GET['shop_name']:''}}" placeholder="দোকান নাম" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="contact_no" value="{{isset($_GET['contact_no'])?$_GET['contact_no']:''}}" placeholder="মোবাইল" class="form-control">
                                    </div>
                                </div>
                                {{-- <div class="col-lg-2 col-sm-6">
                                    <div class="form-group">
                                        <select name="distributor_id" class="select2 form-select">
                                            <option value="">Select Distributor</option>
                                            @forelse ($distributor as $d)
                                                <option value="{{$d->id}}" {{ request('distributor_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6">
                                    <div class="form-group">
                                        <select name="sr_id" class="select2 form-select">
                                            <option value="">Select SR</option>
                                            @forelse ($userSr as $d)
                                                <option value="{{$d->id}}" {{ request('sr_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <select class="select2 form-select" name="area">
                                        <option value="">Select area</option>
                                        @foreach ($area as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col-lg-2 col-sm-6 ps-0 ">
                                    <div class="form-group d-flex">
                                        <button class="btn btn-sm btn-info float-end p-2" type="submit">Search</button>
                                        <a class="btn btn-sm btn-warning ms-2 p-2" href="{{route(currentUser().'.shop.index')}}" title="Clear">Clear</a>
                                   </div>
                                </div>
                                {{-- <div class="col-2 p-0 m-0">
                                </div> --}}
                            </div>
                        </form>
                    </div>
                    <div class="col-1">
                        <a class="float-end" href="{{route(currentUser().'.shop.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>

                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">

                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('দোকান নাম')}}</th>
                                {{-- <th scope="col">{{__('Distributor')}}</th> --}}
                                {{-- <th scope="col">{{__('SR')}}</th> --}}
                                <th scope="col">{{__('মালিক নাম')}}</th>
                                <th scope="col">{{__('মোবাইল')}}</th>
                                {{-- <th scope="col">{{__('DSR')}}</th> --}}
                                {{-- <th scope="col">{{__('Area Name')}}</th> --}}
                                <th scope="col">{{__('পাওনা')}}</th>
                                <th scope="col">{{__('ঠিকানা')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($shop as $data)
                            {{-- in=1 মানে টাকা কালেকশন বা জমা . out=0 মানে বকেয়া দেয়া --}}
                            @php $balance=$data->shopBalances?->where('status',0)->sum('balance_amount') - $data->shopBalances?->where('status',1)->sum('balance_amount')@endphp
                            {{-- @php $shopbalance=($data->shopBalances?->where('status',0)->sum('balance_amount') + $data->shopBalances?->where('status',1)->where('check_type',1)->sum('balance_amount')) - ($data->shopBalances?->where('status',1)->sum('balance_amount') + $data->shopBalances?->where('status',0)->sum('collect_amount')) @endphp --}}
                            @php
                                $oldDue=$data->shopBalances?->where('status',1)->sum('balance_amount');
                                $collectInCash=$data->shopBalances?->where('status',0)->sum('collect_amount');
                                $collectInCheck=$data->shopBalances?->where('status',0)->sum('check_collect_amount');
                                $shopbalance=($data->shopBalances?->where('status',0)->sum('balance_amount') - ($oldDue + $collectInCash + $collectInCheck));
                            @endphp
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$data->shop_name}}</td>
                                {{-- <td>{{$data->distributor?->name}}</td> --}}
                                {{-- <td>{{$data->sr?->name}}</td> --}}
                                <td>{{$data->owner_name}}</td>
                                <td>{{$data->contact}}</td>
                                {{-- <td>{{$data->dsr?->name}}</td> --}}
                                {{-- <td>{{$data->area?->name}}</td> --}}
                                <td>{{ $balance }}</td>
                                <td>{{$data->address}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.shop.edit',encryptor('encrypt',$data->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-primary p-0 m-0" type="button" style="background-color: none; border:none;"
                                    data-bs-toggle="modal" data-bs-target="#balance"
                                    data-shop-name="{{$data->shop_name}}"
                                    data-address="{{$data->address}}"
                                    data-owner-name="{{$data->owner_name}}"
                                    data-shop-id="{{$data->id}}"
                                    data-balance="{{$balance}}"
                                    <span class="text-primary">
                                        {{-- <i class="bi bi-currency-dollar" style="font-size:1rem; color:rgb(49, 49, 245);"></i> --}}জমা
                                    </span>
                                </button>
                                    {{-- <a class="text-danger" href="javascript:void(0)" onclick="confirmDelete({{ $data->id }})">
                                        <i class="bi bi-trash"></i>
                                    </a> --}}
                                    <form id="form{{ $data->id }}" action="{{ route(currentUser().'.shop.destroy', encryptor('encrypt', $data->id)) }}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="10" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="my-3">
                    {!! $shop->withQueryString()->links()!!}
                </div>
                <div class="modal fade" id="balance" tabindex="-1" role="dialog"
                aria-labelledby="balanceTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable"
                    role="document">
                    <form class="form" method="post" action="{{route(currentUser().'.shop.balance')}}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header py-1">
                                <h5 class="modal-title" id="batchTitle">জমা টাকা
                                </h5>
                                <button type="button" class="close text-danger" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x-lg" style="font-size: 1.5rem;"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="bg-light">
                                            <th>সেল সেন্টার নাম:</th>
                                            <td id="shopName"></td>
                                        </tr>
                                        <tr class="bg-light">
                                            <th>মালিকের নাম:</th>
                                            <td id="ownerName"></td>
                                        </tr>
                                        <tr class="bg-light">
                                            <th>বর্তমান টাকা</th>
                                            <td id="shopBalance"></td>
                                        </tr>
                                        <tr class="bg-light" style="display: none;">
                                            <th>Customer ID</th>
                                            <td><input type="hidden" value="" id="shopId" class="form-control" name="shop_id"></td>
                                        </tr>
                                        <tr>
                                            <th>জমা টাকা:</th>
                                            <td ><input type="number" value="{{old('balance')}}" class="form-control" name="balance" placeholder="add balance"></td>
                                        </tr>
                                        <tr>
                                            <th>তারিখ:</th>
                                            <td ><input type="text" id="datepicker" class="form-control" value="<?php print(date("m/d/Y")); ?>"  name="collect_date" placeholder="mm-dd-yyyy"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">Add</button>
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
@push("scripts")
<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this Shop?")) {
            $('#form' + id).submit();
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('#balance').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var shopName = button.data('shop-name');
            var ownerName = button.data('owner-name');
            var shopId = button.data('shop-id');
            var balance = button.data('balance');

            // Set the values in the modal
            var modal = $(this);
            modal.find('#shopName').text(shopName);
            modal.find('#ownerName').text(ownerName);
            modal.find('#shopId').val(shopId);
            modal.find('#shopBalance').text(balance);
        });
    });
</script>
@endpush
