@extends('layout.app')
@section('pageTitle','Distributor All Transfer')
@section('pageSubTitle','List')

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <form class="form" method="get" action="">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12 py-1">
                            <label for="fdate">{{__('From Date')}}</label>
                            <input type="date" id="fdate" class="form-control" value="{{ request('fdate')}}" name="fdate">
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 py-1">
                            <label for="fdate">{{__('To Date')}}</label>
                            <input type="date" id="tdate" class="form-control" value="{{ request('tdate')}}" name="tdate">
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-6 d-flex justify-content-end">
                            <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                        </div>
                        <div class="col-6 d-flex justify-content-Start">
                            <a href="{{route(currentUser().'.supplier.show',encryptor('encrypt',$supplierman->supplier?->id))}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>
                        </div>
                    </div>
                </form>
                <div id="result_show">
                    <table style="width: 100%">
                        <tr>
                            {{-- <td style="width: 15%"><b>Name</b></td>
                            <td style="width: 2%">:</td> --}}
                            <th style="width: 83%">{{ $supplierman->supplier?->name }}</th>
                        </tr>
                        <tr>
                            {{-- <td style="width: 15%"><b>Contact NO</b></td>
                            <td style="width: 2%">:</td> --}}
                            <td style="width: 83%">{{ $supplierman->supplier?->contact }}</td>
                        </tr>
                        <tr>
                            {{-- <td style="width: 15%"><b>Contact NO</b></td>
                            <td style="width: 2%">:</td> --}}
                            <td style="width: 83%">{{ $supplierman->supplier?->address }}</td>
                        </tr>
                        <tr>
                            <th>Openning Balance</th>
                            {{-- <th>0</th> --}}
                            <th>{{ $supplierman->balance_amount }}</th>
                        </tr>
                    </table>
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Reference')}}</th>
                                    <th scope="col">{{__('Balance Date')}}</th>
                                    <th scope="col">{{__('Credit')}}</th>
                                    <th scope="col">{{__('Debit')}}</th>
                                    <th scope="col">{{__('Total')}}</th>
                                    <th scope="col">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $running_balance = 0; // Initialize running balance
                            @endphp
                                @forelse($suplier as $data)
                                    @if ($data->balance_amount != 0)
                                        <tr>
                                            <th scope="row">{{ ++$loop->index }}</th>
                                            <td>{{$data->reference_number}}</td>
                                            @if($data->balance_date)
                                                <td>{{ \Carbon\Carbon::parse($data->balance_date)->format('d/m/Y') }}</td>
                                            @else
                                                <td>No Date Found</td>
                                            @endif
                                            <td>
                                                @if($data->status == 1) 
                                                    {{$data->balance_amount}}
                                                @else
                                                    {{ 0 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->status == 0) 
                                                    {{$data->balance_amount}}
                                                @else
                                                    {{ 0 }} 
                                                @endif
                                            </td>
                                            @php
                                                if ($data->status == 1) {
                                                    $running_balance += $data->balance_amount; 
                                                } else {
                                                    $running_balance -= $data->balance_amount; 
                                                }
                                            @endphp
                                            <td>{{$running_balance}}</td>
                                            <td>
                                                <a class="text-danger" href="javascript:void(0)" onclick="$('#form{{$data->id}}').submit()">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                                <form id="form{{ $data->id }}" onsubmit="return confirm('Are you sure?')" action="{{ route(currentUser().'.supplier.destroy', encryptor('encrypt', $data->id)) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                <tr>
                                    <th colspan="6" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-end"><b>Total Balance:</b> </td>
                                    <td>{{ $suplier->where('status', 1)->sum('balance_amount') - $suplier->where('status', 0)->sum('balance_amount') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
@endsection


