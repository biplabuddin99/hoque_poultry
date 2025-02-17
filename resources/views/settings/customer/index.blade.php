@extends('layout.app')
@section('pageTitle','Customer List')
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
                                <div class="col-4">
                                    <input type="text" name="name" value="{{isset($_GET['name'])?$_GET['name']:''}}" placeholder="Name" class="form-control">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="contact" value="{{isset($_GET['contact'])?$_GET['contact']:''}}" placeholder="Phone No" class="form-control">
                                </div>
                                <div class="col-4 pe-1 d-flex">
                                    <div class="col-2 ps-0">
                                        <button class="btn btn-sm btn-info float-end" type="submit">Search</button>
                                    </div>
                                    <div class="col-2 p-0 m-0">
                                        <a class="btn btn-sm btn-warning ms-2" href="{{route(currentUser().'.customer.index')}}" title="Clear">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class="float-end" href="{{route(currentUser().'.customer.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>

                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">

                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                {{-- <th scope="col">{{__('Customer Code')}}</th> --}}
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Contact')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                {{-- <th scope="col">{{__('Country')}}</th> --}}
                                {{-- <th scope="col">{{__('City')}}</th> --}}
                                <th scope="col">{{__('Balance')}}</th>
                                <th scope="col">{{__('Address')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $data)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                {{-- <td>{{$data->customer_code}}</td> --}}
                                <td>{{$data->name}}</td>
                                <td>{{$data->contact}}</td>
                                <td>{{$data->email}}</td>
                                {{-- <td>{{$data->country}}</td> --}}
                                {{-- <td>{{$data->city}}</td> --}}
                                <td>{{$data->balances?->sum('balance_amount')}}</td>
                                <td>{{$data->address}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.customer.edit',encryptor('encrypt',$data->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn p-0 m-0" type="button" style="background-color: none; border:none;"
                                        data-bs-toggle="modal" data-bs-target="#balance"
                                        data-customer-code="{{$data->customer_code}}"
                                        data-name="{{$data->name}}"
                                        data-customer-id="{{$data->id}}"
                                        data-balance="{{$data->balances?->sum('balance_amount')}}"
                                        <span class="text-primary"><i class="bi bi-currency-dollar" style="font-size:1rem; color:rgb(49, 49, 245);"></i></span>
                                    </button>
                                    {{-- <a href="javascript:void()" onclick="$('#form{{$data->id}}').submit()">
                                        <i class="bi bi-trash"></i>
                                    </a> -->
                                    <form id="form{{$data->id}}" action="{{route(currentUser().'.customer.destroy',encryptor('encrypt',$data->id))}}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>--}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="10" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="pt-2">
                        {{$customers->links()}}
                    </div>
                    <div class="modal fade" id="balance" tabindex="-1" role="dialog"
                        aria-labelledby="balanceTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable"
                            role="document">
                            <form class="form" method="post" action="{{route(currentUser().'.customer.balance')}}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header py-1">
                                        <h5 class="modal-title" id="batchTitle">Add Balance
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
                                                    <th>Customer Name</th>
                                                    <td id="customerName"></td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <th>Customer Code</th>
                                                    <td id="customerCode"></td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <th>Current Balance</th>
                                                    <td id="customerBalance"></td>
                                                </tr>
                                                <tr class="bg-light" style="display: none;">
                                                    <th>Customer ID</th>
                                                    <td><input type="hidden" value="" id="customerId" class="form-control" name="customer_id"></td>
                                                </tr>
                                                <tr>
                                                    <th>Add Balance</th>
                                                    <td ><input type="number" value="{{old('balance')}}" class="form-control" name="balance" placeholder="add balance"></td>
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
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#balance').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var customerCode = button.data('customer-code');
            var name = button.data('name');
            var customerId = button.data('customer-id');
            var balance = button.data('balance');

            // Set the values in the modal
            var modal = $(this);
            modal.find('#customerCode').text(customerCode);
            modal.find('#customerName').text(name);
            modal.find('#customerId').val(customerId);
            modal.find('#customerBalance').text(balance);
        });
    });
</script>
@endpush

