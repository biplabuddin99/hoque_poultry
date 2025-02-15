@extends('layout.app')
@section('pageTitle',trans('Employee leave List'))
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
                            <a class="float-end" href="{{route(currentUser().'.emLeave.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Employee')}}</th>
                                    <th scope="col">{{__('Leave Start')}}</th>
                                    <th scope="col">{{__('Leave End')}}</th>
                                    <th scope="col">{{__('Leave Reason')}}</th>
                                    <th scope="col">{{__('Application Details')}}</th>
                                    <th scope="col">{{__('Application Image')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $p)
                                <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$p->employe?->name}}</td>
                                    <td>{{$p->leave_date_start}}</td>
                                    <td>{{$p->leave_date_end}}</td>
                                    <td>{{$p->leave_reason}}</td>
                                    <td>{{$p->application_details}}</td>
                                    <td><img width="50px" src="{{asset('uploads/LeaveImage/'.company()['company_id'].'/'.$p->application_image)}}" alt=""></td>
                                    <td class="white-space-nowrap">
                                        <a href="{{route(currentUser().'.emLeave.edit',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a class="text-danger" href="javascript:void()" onclick="$('#form{{$p->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$p->id}}" onsubmit="return confirm('Are you sure?')" action="{{route(currentUser().'.emLeave.destroy',encryptor('encrypt',$p->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="8" class="text-center">No Data Found</th>
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

@endsection