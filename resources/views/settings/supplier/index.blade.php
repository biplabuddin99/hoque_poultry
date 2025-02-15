@extends('layout.app')
@section('pageTitle','Distributor List')
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
                                    <input type="text" name="supplier_code" value="{{isset($_GET['supplier_code'])?$_GET['supplier_code']:''}}" placeholder="Distributor Code" class="form-control">
                                </div>

                                <div class="col-2 col-sm-4 ps-0 text-start">
                                    <button class="btn btn-sm btn-info" type="submit">Search</button>
                                    <a class="btn btn-sm btn-warning " href="{{route(currentUser().'.supplier.index')}}" title="Clear">Clear</a>
                                </div>
                                <div class="col-2 p-0 m-0">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class="float-end" href="{{route(currentUser().'.supplier.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>

                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">

                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Distributor Code')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Contact')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Country')}}</th>
                                <th scope="col">{{__('City')}}</th>
                                <th scope="col">{{__('Balance')}}</th>
                                <th scope="col">{{__('Address')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $data)
                            @php $balance=$data->balances?->where('status',1)->sum('balance_amount') - $data->balances?->where('status',0)->sum('balance_amount') @endphp
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$data->supplier_code}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->contact}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->country}}</td>
                                <td>{{$data->city}}</td>
                                <td>{{$balance}}</td>
                                <td>{{$data->address}}</td>
                                <td class="white-space-nowrap">
                                    <a class="ms-2" href="{{route(currentUser().'.supplier.show',encryptor('encrypt',$data->id))}}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{route(currentUser().'.supplier.edit',encryptor('encrypt',$data->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn p-0 m-0" type="button" style="background-color: none; border:none;"
                                        data-bs-toggle="modal" data-bs-target="#balance"
                                        data-supplier-code="{{$data->supplier_code}}"
                                        data-name="{{$data->name}}"
                                        data-supplier-id="{{$data->id}}"
                                        data-balance="{{$data->balances?->sum('balance_amount')}}"
                                        <span class="text-primary"><i class="bi bi-currency-dollar" style="font-size:1rem; color:rgb(49, 49, 245);"></i></span>
                                    </button>
                                    {{-- <a href="javascript:void()" onclick="$('#form{{$data->id}}').submit()">
                                        <i class="bi bi-trash"></i>
                                    </a> -->
                                    <form id="form{{$data->id}}" action="{{route(currentUser().'.supplier.destroy',encryptor('encrypt',$data->id))}}" method="post">
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
                        {{$suppliers->links()}}
                    </div>
                    <div class="modal fade" id="balance" tabindex="-1" role="dialog"
                        aria-labelledby="balanceTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable"
                            role="document">
                            <form class="form" method="post" action="{{route(currentUser().'.supplier.balance')}}">
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
                                                    <th>Supplier Name</th>
                                                    <td id="supplierName"></td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <th>Supplier Code</th>
                                                    <td id="supplierCode"></td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <th>Current Balance</th>
                                                    <td id="supplierBalance"></td>
                                                </tr>
                                                <tr class="bg-light" style="display: none;">
                                                    <th>Supplier ID</th>
                                                    <td><input type="hidden" value="" id="supplierId" class="form-control" name="supplier_id"></td>
                                                </tr>
                                                <tr>
                                                    <th>Reference Number</th>
                                                    <td ><input type="text" value="{{old('reference_number')}}" class="form-control" name="reference_number" placeholder="add reference_number"></td>
                                                    <th>Balance Date</th>
                                                    <td ><input type="date" value="{{old('balance_date')}}" class="form-control" name="balance_date" placeholder="add balance_date"></td>
                                                </tr>
                                                <tr>
                                                    <th>Add Balance</th>
                                                    <td ><input type="number" value="{{old('balance')}}" class="form-control" name="balance" placeholder="add balance"></td>
                                                    <th>Bill Term</th>
                                                    <td>
                                                        <select name="billterm_id" id="" class="form-control">
                                                            <option value="">select</option>
                                                            @foreach ($bill_term as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
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
            var supplierCode = button.data('supplier-code');
            var name = button.data('name');
            var supplierId = button.data('supplier-id');
            var balance = button.data('balance');

            // Set the values in the modal
            var modal = $(this);
            modal.find('#supplierCode').text(supplierCode);
            modal.find('#supplierName').text(name);
            modal.find('#supplierId').val(supplierId);
            modal.find('#supplierBalance').text(balance);
        });
    });
</script>
@endpush

