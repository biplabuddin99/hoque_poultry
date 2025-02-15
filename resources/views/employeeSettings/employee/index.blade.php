@extends('layout.app')
@section('pageTitle',trans('Employee List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <a class="float-end" href="{{route(currentUser().'.employee.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Employee Code')}}</th>
                                    <th scope="col">{{__('Designation')}}</th>
                                    <th scope="col">{{__('Name')}}</th>
                                    <th scope="col">{{__('Father Name')}}</th>
                                    <th scope="col">{{__('Mother Name')}}</th>
                                    <th scope="col">{{__('Contact')}}</th>
                                    <th scope="col">{{__('Area')}}</th>
                                    <th scope="col">{{__('Address')}}</th>
                                    <th scope="col">{{__('Status')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $p)
                                <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$p->employee_code}}</td>
                                    <td>{{$p->designa?->name}}</td>
                                    <td>{{$p->name}}</td>
                                    <td>{{$p->father_name}}</td>
                                    <td>{{$p->mother_name}}</td>
                                    <td>{{$p->contact}}</td>
                                    <td>{{$p->area}}</td>
                                    <td>{{$p->permanent_address}}</td>
                                    <td>@if($p->status == 1) {{__('Active') }} @else {{__('Inactive') }} @endif</td>

                                    <td class="white-space-nowrap">
                                        <a href="{{route(currentUser().'.employee.edit',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a class="text-danger" href="javascript:void()" onclick="$('#form{{$p->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$p->id}}" onsubmit="return confirm('Are you sure?')" action="{{route(currentUser().'.employee.destroy',encryptor('encrypt',$p->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="11" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="my-3">
                        {!! $data->links()!!}
                    </div>
                </div>
            </div>
    </div>
</section>
<!-- Bordered table end -->


@endsection