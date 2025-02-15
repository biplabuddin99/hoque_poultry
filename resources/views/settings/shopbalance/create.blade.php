@extends('layout.app')
@section('pageTitle','Collection Shop')
@section('pageSubTitle','Collection')
@section('content')
<style>
    .hidden{
        display: none;
    }
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.shopbalance.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="shop_name">Collection By <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" name="user_id">
                                            <option value="">Select</option>
                                            @foreach ($users as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }} - {{$s->contact_no}} ({{ $s?->role?->type }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- @if($errors->has('shop_name'))
                                        <span class="text-danger"> {{ $errors->first('shop_name') }}</span>
                                    @endif --}}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="shop_name">Shop Name(Owner)<span class="text-danger">*</span></label>
                                        <select class="select2 form-select shop_id" name="shop_id">
                                            <option value="">Select</option>
                                            @foreach ($shops as $s)
                                            <option value="{{ $s->id }}">{{ $s->shop_name }}({{ $s->owner_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- @if($errors->has('shop_name'))
                                        <span class="text-danger"> {{ $errors->first('shop_name') }}</span>
                                    @endif --}}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="balance_amount">Amount<span class="text-danger">*</span></label>
                                        <input type="number" value="{{old('balance_amount')}}" class="form-control border border-primary" name="balance_amount" placeholder="Amount" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="shop_name">Cash Type<span class="text-danger">*</span></label>
                                        <select class="form-select shop_id" name="cash_type" id="cash_type" required>
                                            <option value="">Select</option>
                                            <option value="0">Cash</option>
                                            <option value="1">Check</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6" id="check_number_container" >
                                    <div class="form-group">
                                        <label for="">Check Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('check_number')}}" name="check_number" placeholder="Check Number">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" value="{{ old('new_collect_date')}}" name="new_collect_date" placeholder="Date">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded',function(){
        let cashTypeSelect = document.getElementById('cash_type');
        let checkNumber = document.getElementById('check_number_container');

        cashTypeSelect.addEventListener('change',function(){
            if(cashTypeSelect.value === '1'){
                checkNumber.style.display = 'block';
            }else{
                checkNumber.style.display = 'none';
            }
        });
        cashTypeSelect.dispatchEvent(new Event('change'));
    });
</script>
@endsection
