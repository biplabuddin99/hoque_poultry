@extends('layout.app')
@section('pageTitle',trans('Return Product List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <form action="">
                    <div class="row mb-2">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <select name="supplier_id" class="form-control form-select">
                                    <option value="">Select Customer</option>
                                    @forelse ($distributor as $d)
                                        <option value="{{$d->id}}" {{ request('supplier_id')==$d->id?"selected":""}}>{{$d->name}}</option>
                                    @empty
                                        <option value="">No Data Found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <input type="text" class="form-control" name="invoice_number" placeholder="invoice">
                        </div>
                        <div class="col-lg-2 col-sm-6 ps-0 ">
                            <div class="form-group d-flex">
                                <button class="btn btn-info float-end" type="submit">Search</button>
                                <a class="btn btn-danger ms-2" href="{{route(currentUser().'.returnproduct.index')}}" title="Clear">Clear</a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            {{-- space --}}
                        </div>
                        
                        <div class="col-lg-2 col-sm-6 d-flex justify-content-end align-items-center">
                            <a class="text-primary" href="{{route(currentUser().'.returnproduct.create')}}">
                                <i class="bi bi-plus-square-fill" style="font-size: 1.7rem;"></i>
                            </a>
                        </div>
                    </div>
                </form>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        {{-- <a class="float-end" href="{{route(currentUser().'.returnproduct.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a> --}}
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Distributor')}}</th>
                                <th scope="col">{{__('Return Type')}}</th>
                                <th scope="col">{{__('Driver Name')}}</th>
                                <th scope="col">{{__('Helper')}}</th>
                                <th scope="col">{{__('Invoice Number')}}</th>
                                <th scope="col">{{__('Total')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $p)
                                <tr>
                                    <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$p->distributor?->name}}</td>
                                    <td>
                                        @if($p->return_type == 0)
                                            Damage
                                        @elseif ($p->return_type == 1)
                                            Airless
                                        @elseif ($p->return_type == 2)
                                            Short Date
                                        @else
                                        @endif
                                    </td>
                                    <td>{{$p->driver_name}}</td>
                                    <td>{{$p->helper}}</td>
                                    <td>{{$p->invoice_number}}</td>
                                    <td>{{$p->total}}</td>
                                    <td class="white-space-nowrap">
                                        @if ($p->status == 0)
                                            <a class="pe-1" href="{{route(currentUser().'.get_return_receive',encryptor('encrypt',$p->id))}}">
                                                <i class="bi bi-receipt-cutoff"></i>
                                            </a>
                                        @else
                                            <a class="pe-1" href="{{route(currentUser().'.get_return_receive_partial',encryptor('encrypt',$p->id))}}">
                                                <i class="bi bi-receipt-cutoff"></i>
                                            </a>
                                        @endif
                                        <a class="px-1" href="{{route(currentUser().'.returnproduct.show',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @if ($p->status != 1)
                                            <a class="ps-1" href="{{route(currentUser().'.returnproduct.edit',encryptor('encrypt',$p->id))}}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <th colspan="7" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="my-3">
                     {!! $data->withQueryString()->links()!!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
