@extends('layout.app')

@section('pageTitle',trans('Search Sales Closing'))
@section('pageSubTitle',trans('Create'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.getSalesClosingData')}}">
                            @csrf
                            <div class="row p-2 mt-4">
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Shop/Dsr</b></label>
                                    <select class="form-select" onclick="getShopDsr()" name="select_shop_dsr">
                                        <option value="">Select</option>
                                        <option value="shop">Shop</option>
                                        <option value="dsr">DSR</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2" id="shopNameContainer" style="display: none;">
                                    <label for=""><b>Shop Name</b></label>
                                    <select class="form-select shop_id" name="shop_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2" id="dsrNameContainer" style="display: none;">
                                    <label for=""><b>DSR Name</b></label>
                                    <select class="form-select dsr_id" name="dsr_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><b>Sales Date</b></label>
                                    <input type="text" id="datepicker" class="form-control" value="<?php print(date("m/d/Y")); ?>"  name="sales_date" placeholder="mm-dd-yyyy">
                                </div>
                                <div class="col-lg-3 mt-4">
                                    <button type="submit" class="btn btn-primary">GO</button>
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
@push("scripts")
<script>
    function getShopDsr() {
        var selectedOption = document.querySelector('select[name="select_shop_dsr"]').value;

        var shopNameContainer = document.getElementById("shopNameContainer");
        var shopDropdown = document.querySelector('select[name="shop_id"]');
        var dsrNameContainer = document.getElementById("dsrNameContainer");
        var dsrDropdown = document.querySelector('select[name="dsr_id"]');

        if (selectedOption === "shop") {
            shopNameContainer.style.display = "block";
            dsrNameContainer.style.display = "none";
            dsrDropdown.value = "";
            getShopData();
        } else if (selectedOption === "dsr") {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "block";
            shopDropdown.value = "";
            getDsrData();
        } else {
            shopNameContainer.style.display = "none";
            dsrNameContainer.style.display = "none";
        }
    }
    function getShopData() {
        $.ajax({
            url: "{{ route(currentUser().'.get_shop') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                populateShopOptions(data);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    function getDsrData() {
        $.ajax({
            url: "{{ route(currentUser().'.get_dsr') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {
                populateDsrOptions(data);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    function populateShopOptions(data) {
        var selectElement = document.querySelector('select[name="shop_id"]');
        selectElement.innerHTML = "";

        data.forEach(function(item) {
            var option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.shop_name;
            selectElement.appendChild(option);
        });
    }

    function populateDsrOptions(data) {
        var selectElement = document.querySelector('select[name="dsr_id"]');
        selectElement.innerHTML = "";

        data.forEach(function(item) {
            var option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.name;
            selectElement.appendChild(option);
        });
    }

</script>
@endpush
