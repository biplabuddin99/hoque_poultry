@extends('layout.app')

@section('pageTitle',trans('No Data Found'))
@section('pageSubTitle',trans('Show'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                            <div class="row p-2 mt-4">
                                <div class="col-lg-12 my-2 text-center">
                                    <h2>This Date NO Sales</h2>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

