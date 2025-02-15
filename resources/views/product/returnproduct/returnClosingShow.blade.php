@extends('layout.app')

@section('pageTitle',trans('Return Closing Invoice'))
@section('pageSubTitle',trans('invoice'))

@section('content')
<section id="result_show">
    <style>

        .tbl_border{
        border: 1px solid rgb(46, 46, 46);
        border-collapse: collapse;
        }
    </style>
@php $settings=App\Models\Settings\Company::first(); @endphp
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row text-center">
                            <h5> <span class="bg-secondary text-white p-1">Return Invoice</span></h5>
                            <h3 class="mb-1">{{ $return->distributor?->name }}</h3>
                            <p>Address :{{ $settings->address }}</p>
                        </div>
                        <div class="row mt-4">
                            <!-- table bordered -->
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td style="width: 18%"><b>Driver</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 40%">{{ $return->driver_name}}</td>
                                        <td style="width: 18%"><b>Gari No</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">{{ $return->garir_number}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 18%"><b>Helper</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 40%">{{ $return->helper}}</td>
                                        <td style="width: 18%"><b>Invoice</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">{{ $return->invoice_number}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 18%"><b>Note</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="">{{ $return->note}}</td>
                                        <td style="width: 18%"><b>Type</b></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 20%">
                                            @if($return->return_type == 0)
                                                Damage
                                            @elseif ($return->return_type == 1)
                                                Normal
                                            @elseif ($return->return_type == 2)
                                                Manu fault
                                            @else
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                <table class="table tbl_border mb-0">
                                    <thead>
                                        <tr class="text-center tbl_border bg-secondary text-white">
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('ক্রমিক নং')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('পণ্যের নাম')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('কাটুন')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('পিছ')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('টোটাল পিছ')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('প্রাইস')}}</th>
                                            <th class="tbl_border" style="text-align: center; padding: 5px;" scope="col">{{__('টাকা')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($returnDetails as $d)
                                        @php
                                            $data = $d->total_pcs_return - $d->total_receive_qty;
                                        @endphp
                                            @if ($data > 0)
                                                <tr class="text-center">
                                                    <td class="tbl_border">{{ ++$loop->index }}</td>
                                                    <td class="tbl_border">{{ $d->product?->product_name }}</td>
                                                    <td class="tbl_border">{{ $d->ctn_return }}</td>
                                                    <td class="tbl_border">{{ $d->pcs_return }}</td>
                                                    <td class="tbl_border">{{ $d->total_pcs_return }}</td>
                                                    <td class="tbl_border">{{ $d->price }}</td>
                                                    <td class="tbl_border">{{ $d->amount }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="d-flex justify-content-between mt-5">
                                <span style="border-top: solid 2px;">DSR Signature</span>
                                <span style="border-top: solid 2px;">Authorize Signature</span>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
@endsection