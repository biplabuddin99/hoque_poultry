@extends('layout.app')

@section('pageTitle',trans('Create Category'))
@section('pageSubTitle',trans('Create'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.category.store')}}">
                            @csrf
                            <div class="row">
                                <h5>Details</h5>
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <div class="d-flex justify-content-between">
                                        <label class="py-2" for="cat">{{__('Supplier')}}<span class="text-danger">*</span></label>
                                        <button class="btn p-0 m-0" type="button" style="background-color: none; border:none;" data-bs-toggle="modal"
                                        data-bs-target="#supplier">
                                            <span class="text-primary"><i class="bi bi-plus-square-fill" style="font-size:1.5rem;"></i></span>
                                        </button>
                                    </div>
                                    <div class="form-group mb-3">
                                        <select class=" choices form-select" name="supplier">
                                            <option value="">Select Suppliers</option>
                                            <option value="1">common supplier</option>
                                            <option value="1">regular</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-2" for="cat">{{__('Purchase Type')}}<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" name="name">
                                            <option value="">Select type</option>
                                            <option value="1">common supplier</option>
                                            <option value="1">regular</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-2" for="cat">{{__('Bill Terms')}}<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" name="name">
                                            <option value="0">Cash</option>
                                            <option value="1">Credit</option>
                                            <option value="2">Card</option>
                                            <option value="3">Bkash</option>
                                            <option value="4">Rocket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-2" for="cat">{{__('Purchase Date')}}<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="purchase_date" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-2" for="cat">{{__('Supplier Tax/Vat Number')}}</label>
                                        <input type="text" class="form-control" name="supplier_tax" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="product">{{__('Product')}}<span class="text-danger">*</span></label>
                                        <select class=" choices form-select" name="product">
                                            <option value="">Select Product</option>
                                            <option value="1">common supplier</option>
                                            <option value="1">regular</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex justify-content-between">
                                        <label class="py-2" for="cat">{{__('Batch')}}<span class="text-danger">*</span></label>
                                        <button class="btn p-0 m-0" type="button" style="background-color: none; border:none;" data-bs-toggle="modal"
                                        data-bs-target="#batch">
                                            <span class="text-primary"><i class="bi bi-plus-square-fill" style="font-size:1.5rem;"></i></span>
                                        </button>
                                    </div>
                                    <div class="form-group mb-3">
                                        <select class=" choices form-select" name="batch">
                                            <option value="">Select Batch</option>
                                            <option value="1">batch1</option>
                                            <option value="1">batch2</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="qty">{{__('Qty')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="qty">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="price">{{__('Total Price')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="total_price">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="free">{{__('Free')}}</label>
                                        <input type="text" class="form-control" name="free">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="price">{{__('Price')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="price" placeholder="price">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="basic">{{__('Basic')}}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="basic">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="discount">{{__('Dis%')}}</label>
                                        <input type="text" class="form-control" name="discount">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group mb-3">
                                        <label class="py-2" for="tax">{{__('Tax%')}}</label>
                                        <input type="text" class="form-control" name="tax">
                                    </div>
                                </div>
                                <div class="col-lg-2 ps-0">
                                    <label class="py-2" for="amount"><b>{{__('Amount')}}</b></label><br>
                                    <div class="form-group mb-3 d-flex justify-content-between">
                                        <input type="text" class="form-control p-0" name="amount" readonly style="width:82%;display:inline-block">
                                        <a  href=""><i class="bi bi-trash-fill" style="font-size:1.5rem; color:rgb(230, 5, 5)"></i></a>

                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <a  href=""><i class="bi bi-plus-square-fill" style="font-size:2rem;"></i></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4 class="header-title">Total Bill</h4>
                                    <div class="total-payment">
                                        <table class="table table-sm table-borderless ">
                                            <tbody>
                                                <tr>
                                                    <td width="20%">Subtotal</td>
                                                    <td width="2%">:</td>
                                                    <td width="78%"><input type="text" class="form-control" name="sub_total"></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Vat/Tax</td>
                                                    <td width="2%">:</td>
                                                    <td width="78%"><input type="text" class="form-control" name="sub_total"></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Discount</td>
                                                    <td width="2%">:</td>
                                                    <td width="78%"><input type="text" class="form-control" name="discount"></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Other Charge</td>
                                                    <td width="2%">:</td>
                                                    <td width="78%"><input type="text" class="form-control" name="chagre"></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Paid</td>
                                                    <td width="2%">:</td>
                                                    <td width="78%"><input type="text" class="form-control" name="paid"></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Change/Due</td>
                                                    <td width="2%">:</td>
                                                    <td width="78%"><input type="text" class="form-control" name="paid"></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" colspan="2"><a class="btn btn-primary btn-block" href="">Save</a></td>
                                                    <td width="78%"><a class="btn btn-info btn-block" href="">Save & Print</a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <div class="total">
                                        <table class="table table-sm table-borderless ">
                                            <tbody>
                                                <tr>
                                                    <td><h4>Total</h4></td>
                                                    <td><input type="text" class="form-control" name="" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><h4>In Word</h4></td>
                                                    <td><input type="text" class="form-control" name="" value="0"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="py-2">Pin Code</label>
                                                <input type="text" class="form-control" name="" placeholder="pin code">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group mb-3">
                                                <label for="" class="py-2">Delivery Address</label>
                                                <input type="text" class="form-control" name="" placeholder="delivery address">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="py-2">Checque No</label>
                                                <input type="text" class="form-control" name="" placeholder="checque no">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="py-2">Checque No</label>
                                                <input type="text" class="form-control" name="" placeholder="checque no">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="py-2">Checque No</label>
                                                <input type="text" class="form-control" name="" placeholder="checque no">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-3">
                                                <label for="" class="py-2">Checque No</label>
                                                <textarea class="form-control" name="" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="supplier" tabindex="-1" role="dialog"
                aria-labelledby="supplierTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                    role="document">
                    <form action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="supplierTitle">Add New Supplier
                                </h5>
                                <button type="button" class="close text-danger" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="code">Supplier Code<span class="text-danger">*</span></label>
                                            <input type="text" value="{{old('supplier_code')}}" class="form-control" name="supplier_code" required>
                                        </div>
                                        {{-- @if($errors->has('name'))
                                            <span class="text-danger"> {{ $errors->first('name') }}</span>
                                        @endif --}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Supplier Name<span class="text-danger">*</span></label>
                                            <input type="text" value="{{old('name')}}" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="contact">Contact No</label>
                                            <input type="text" value="{{old('contact')}}" class="form-control" name="contact">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" value="{{old('email')}}" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="balance">Opening Balance</label>
                                            <input type="number" value="{{old('balance')}}" class="form-control" name="balance">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <input type="text" value="{{old('country')}}" class="form-control" name="country">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" value="{{old('city')}}" class="form-control" name="city">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" name="address" rows="2">{{old('address')}}</textarea>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Submit</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="batch" tabindex="-1" role="dialog"
                aria-labelledby="batchTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                    role="document">
                    <form action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="batchTitle">Add New Batch
                                </h5>
                                <button type="button" class="close text-danger" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                               <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Possimus voluptatum, similique eos, dolore, pariatur amet reiciendis voluptate veniam maxime perferendis tempore ullam quibusdam. Recusandae alias, necessitatibus quo laboriosam vel fuga excepturi minima. Sunt debitis nobis animi aspernatur reiciendis inventore eveniet odio. Aliquid distinctio aliquam consequuntur, minus doloremque quasi fugit porro.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Submit</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-element-select.js') }}"></script>
@endpush
