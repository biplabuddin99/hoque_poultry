@extends('layout.app')
@section('pageTitle',trans('Receive List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
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
                    </div>
                    <div class="row m-4">
                        <div class="col-6 d-flex justify-content-end">
                            <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>

                        </div>
                        <div class="col-6 d-flex justify-content-Start">
                            <a href="{{route(currentUser().'.do.receivelist')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Clear')}}</a>

                        </div>
                    </div>
                </form>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 table-striped">
                        {{--  <a class="float-end" href="{{route(currentUser().'.docontroll.create')}}" style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>  --}}
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Chalan No')}}</th>
                                <th scope="col">{{__('Distributor')}}</th>
                                <th scope="col">{{__('Stock Date')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key=>$p)
                            <tr>
                                <th scope="row">{{ $data->firstItem() + $key }}</th>
                                <td>{{$p->chalan_no}}</td>
                                <td>{{$p->supplier?->name}}</td>
                                <td>{{$p->stock_date}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.showDoReceive',$p->chalan_no)}}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($p->do_rec_uni_code != '')
                                        <a href="{{ route(currentUser().'.get_rec_edit', $p->do_rec_uni_code) }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="5" class="text-center">No Data Found</th>
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
