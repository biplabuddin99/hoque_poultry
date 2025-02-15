@extends('layout.app')
@section('pageTitle','Unit Style List')
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
                                <div class="col-6">
                                    <input type="text" name="name" value="{{isset($_GET['name'])?$_GET['name']:''}}" placeholder="Name" class="form-control">
                                </div>

                                <div class="col-2 ps-0">
                                    <button class="btn btn-sm btn-info float-end" type="submit">Search</button>
                                </div>
                                <div class="col-4 p-0 m-0">
                                    <a class="btn btn-sm btn-warning ms-2" href="{{route(currentUser().'.unitstyle.index')}}" title="Clear">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class="float-end" href="{{route(currentUser().'.unitstyle.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>

                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">

                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Qty')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unitstyles as $data)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$data->name}}</td>
                                <td>{{$data->unit?->qty}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.unitstyle.edit',encryptor('encrypt',$data->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    {{-- <a href="javascript:void()" onclick="$('#form{{$data->id}}').submit()">
                                        <i class="bi bi-trash"></i>
                                    </a> -->
                                    <form id="form{{$data->id}}" action="{{route(currentUser().'.unitstyle.destroy',encryptor('encrypt',$data->id))}}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>--}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="4" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="pt-2">
                        {{$unitstyles->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

