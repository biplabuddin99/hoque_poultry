@extends('layout.app')

@section('pageTitle','আপডেট করুন Shop')
@section('pageSubTitle','Update')

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.shop.update',encryptor('encrypt',$shop->id))}}">
                            @csrf
                            @method('patch')
                            <div class="row">
                                {{-- <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="sup_id">Distributor<span class="text-danger">*</span></label>
                                        <select class="form-select border border-primary" name="sup_id" onchange="srShow();" required>
                                            <option value="">Select</option>
                                            @forelse (App\Models\Settings\Supplier::where(company())->get() as $sup)
                                                <option value="{{ $sup->id }}" {{ $shop->sup_id==$sup->id?'selected':'' }}>{{ $sup->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="sr_id">SR <span class="text-danger">*</span></label>
                                        <select class="form-select border border-primary" name="sr_id" id="srUser_id">
                                            <option value="">Select</option>
                                            @forelse (\App\Models\User::where(company())->where('role_id',5)->get() as $sr)
                                                <option class="selecet_hide selecet_hide{{$sr->distributor_id}}" value="{{ $sr->id }}" {{ $shop->sr_id==$sr->id?'selected':'' }}>{{ $sr->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="owner_name">মালিক নাম<span class="text-danger">*</span></label>
                                        <input type="text" value="{{old('owner_name',$shop->owner_name)}}" class="form-control border border-primary" name="owner_name" placeholder="Owner Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="shop_name">দোকান নাম<span class="text-danger">*</span></label>
                                        <input type="text" value="{{old('shop_name',$shop->shop_name)}}" class="form-control border border-primary" name="shop_name" placeholder="Shop Name" required>
                                    </div>
                                    {{-- @if($errors->has('shop_name'))
                                        <span class="text-danger"> {{ $errors->first('shop_name') }}</span>
                                    @endif --}}
                                </div>
                                {{-- <div class="col-lg-4 col-md-6 col-sm-6 d-none">
                                    <div class="form-group">
                                        <label for="dsr_id">DSR <span class="text-danger">*</span></label>
                                        <select class="form-select border border-primary" name="dsr_id">
                                            <option value="">Select Product</option>
                                            @forelse (\App\Models\User::where(company())->where('role_id',4)->get() as $dsr)
                                            <option value="{{ $dsr->id }}"{{ $shop->dsr_id==$dsr->id?'selected':'' }}>{{ $dsr->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="contact">মোবাইল নাম্বার:</label>
                                        <input type="text" value="{{old('contact',$shop->contact)}}" class="form-control border border-primary" placeholder="Contact" name="contact">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="balance">Opening Balance</label>
                                        <input type="number" value="{{old('balance',$shop->balance)}}" class="form-control border border-primary" name="balance">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="address">ঠিকানা</label>
                                        <textarea class="form-control border border-primary" name="address" rows="2">{{old('address',$shop->address)}}</textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="area_name">Area Name</label>
                                        <select name="area_name" class="form-control form-select" id="area_id">
                                            <option value="">Select area</option>
                                            @foreach ($area as $a)
                                                <option class="selecet_hide selecet_hide{{$a->distributor_id}}" value="{{ $a->id}}" {{ $shop->area_name==$a->id?'selected':'' }}>{{$a->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info me-1 mb-1">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    let old_supplier_id=0;
    /* call on load page */
    $(document).ready(function(){
       $('.selecet_hide').hide();
   })
   window.onload = function() {
        old_supplier_id = document.querySelector('select[name="sup_id"]').value;
        srShow();
    };

   function srShow(){
        let supplier = document.querySelector('select[name="sup_id"]').value;
         $('.selecet_hide').hide();
         $('.selecet_hide'+supplier).show();
         if(old_supplier_id!=supplier){
            $('#srUser_id').prop('selectedIndex', 0);
            $('#area_id').prop('selectedIndex', 0);
             old_supplier_id=supplier;
         }
    }

</script>
@endpush
