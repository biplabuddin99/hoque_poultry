<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController as auth;
use App\Http\Controllers\DashboardController as dash;
use App\Http\Controllers\Settings\CompanyController as company;
use App\Http\Controllers\Settings\WerehouseController as werehouse;
use App\Http\Controllers\Settings\BillTermController as bill;
use App\Http\Controllers\Settings\UnitStyleController as unitstyle;
use App\Http\Controllers\Settings\UnitController as unit;
use App\Http\Controllers\Settings\SupplierController as supplier;
use App\Http\Controllers\Settings\CustomerController as customer;
use App\Http\Controllers\Settings\ShopController as shop;
use App\Http\Controllers\Settings\ShopBalanceController as shopbalance;
use App\Http\Controllers\Settings\UserController as user;
use App\Http\Controllers\Settings\AdminUserController as admin;
use App\Http\Controllers\Settings\Location\CountryController as country;
use App\Http\Controllers\Settings\Location\DivisionController as division;
use App\Http\Controllers\Settings\Location\DistrictController as district;
use App\Http\Controllers\Settings\Location\UpazilaController as upazila;
use App\Http\Controllers\Settings\Location\ThanaController as thana;
use App\Http\Controllers\Settings\Location\AreaController as area;
use App\Http\Controllers\Employee\DesignationController as designation;
use App\Http\Controllers\Employee\EmployeeController as employee;
use App\Http\Controllers\Employee\EmployeeLeaveController as emLeave;
use App\Http\Controllers\Currency\CurrencyController as currency;

use App\Http\Controllers\Product\CategoryController as category;
use App\Http\Controllers\Product\GroupController as group;
use App\Http\Controllers\Product\ProductController as product;
use App\Http\Controllers\Product\BatchController as batch;
use App\Http\Controllers\Product\ReturnProductController as returnproduct;
use App\Http\Controllers\Do\DOController as docon;
use App\Http\Controllers\Reports\ReportController as report;


use App\Http\Controllers\Sales\SalesController as sales;
use App\Http\Controllers\Sales\CheckDetailsController as checkDetail;


use App\Http\Controllers\Accounts\MasterAccountController as master;
use App\Http\Controllers\Accounts\SubHeadController as sub_head;
use App\Http\Controllers\Accounts\ChildOneController as child_one;
use App\Http\Controllers\Accounts\ChildTwoController as child_two;
use App\Http\Controllers\Accounts\NavigationHeadViewController as navigate;
use App\Http\Controllers\Accounts\IncomeStatementController as statement;

use App\Http\Controllers\Vouchers\CreditVoucherController as credit;
use App\Http\Controllers\Vouchers\DebitVoucherController as debit;
use App\Http\Controllers\Vouchers\JournalVoucherController as journal;
/* Middleware */
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isOwner;
use App\Http\Middleware\isManager;
use App\Http\Middleware\isAccountant;
use App\Http\Middleware\isJso;
use App\Http\Middleware\isSalesrepresentative;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [auth::class,'signUpForm'])->name('register');
Route::post('/register', [auth::class,'signUpStore'])->name('register.store');
Route::get('/', [auth::class,'signInForm'])->name('signIn');
Route::get('/login', [auth::class,'signInForm'])->name('login');
Route::post('/login', [auth::class,'signInCheck'])->name('login.check');
Route::get('/logout', [auth::class,'singOut'])->name('logOut');


Route::group(['middleware'=>isAdmin::class],function(){
    Route::prefix('admin')->group(function(){
        Route::get('/dashboard', [dash::class,'adminDashboard'])->name('admin.dashboard');
        /* settings */
        Route::get('/admincompany',[company::class,'admindex'])->name('admin.admincompany');


       // Route::resource('/profile/update',profile::class,['as'=>'admin']);

        Route::resource('users',user::class,['as'=>'admin']);
        Route::resource('admin',admin::class,['as'=>'admin']);
        Route::resource('country',country::class,['as'=>'admin']);
        Route::resource('division',division::class,['as'=>'admin']);
        Route::resource('district',district::class,['as'=>'admin']);
        Route::resource('upazila',upazila::class,['as'=>'admin']);
        Route::resource('thana',thana::class,['as'=>'admin']);
        Route::resource('unit',unit::class,['as'=>'admin']);
        Route::resource('currency',currency::class,['as'=>'admin']);

    });
});

Route::group(['middleware'=>isOwner::class],function(){
    Route::prefix('owner')->group(function(){
        Route::get('/dashboard', [dash::class,'ownerDashboard'])->name('owner.dashboard');

        //hoque paultry all route
                Route::resource('company',company::class,['as'=>'owner']);
                Route::resource('customer',customer::class,['as'=>'owner']);
                Route::resource('shop',shop::class,['as'=>'owner']);
                Route::resource('sales',sales::class,['as'=>'owner']);
                Route::get('sales-update/{id}',[sales::class,'salesUpdate'])->name('owner.sales.sales_update');
                Route::post('sales-updatestore/{id}',[sales::class,'salesUpdateStore'])->name('owner.sales.salesUpdate');
                //ajax call
                Route::get('product-get',[sales::class,'getproduct'])->name('owner.getproduct');

        // settings
        // Route::resource('company',company::class,['as'=>'owner']);
        Route::resource('area',area::class,['as'=>'owner']);
        Route::resource('unitstyle',unitstyle::class,['as'=>'owner']);
        Route::resource('unit',unit::class,['as'=>'owner']);
        Route::resource('werehouse',werehouse::class,['as'=>'owner']);
        Route::resource('bill',bill::class,['as'=>'owner']);
        Route::resource('users',user::class,['as'=>'owner']);
        Route::resource('supplier',supplier::class,['as'=>'owner']);

        Route::resource('shopbalance',shopbalance::class,['as'=>'owner']);
        Route::post('collection-by-update',[shopbalance::class,'collectionByUpdate'])->name('owner.collection_by_update');
        Route::get('collect-index',[shopbalance::class,'collectIndex'])->name('owner.collect_index');
        // Route::resource('checkCollection',shopbalance::class,['as'=>'owner']);
        Route::post('/customer/balance', [customer::class, 'customerBalance'])->name('owner.customer.balance');
        Route::post('/supplier/balance', [supplier::class, 'supplierBalance'])->name('owner.supplier.balance');

        //sales

        Route::get('selected-sales-index',[sales::class,'selectedIndex'])->name('owner.selectedIndex');
        Route::get('selected-sales-get',[sales::class,'selectedCreate'])->name('owner.selectedCreate');
        Route::get('selected-sales-edit/{id}',[sales::class,'selectedEdit'])->name('owner.selectedEdit');
        Route::get('selected-sales-show/{id}',[sales::class,'selectedShow'])->name('owner.selectedShow');
        Route::get('sales-primary-update/{id}',[sales::class,'PrimaryUpdate'])->name('owner.sales.primary_update');
        Route::post('sales-primary-store/{id}',[sales::class,'primaryStore'])->name('owner.sales.primaryStore');
        Route::get('sales-receive-screen/{id}',[sales::class,'salesReceiveScreen'])->name('owner.sales.receiveScreen');
        Route::get('selected-receive-screen/{id}',[sales::class,'selectedReceiveScreen'])->name('owner.sales.selectedReceiveScreen');



        Route::get('sales-print-page/{id}',[sales::class,'printSalesClosing'])->name('owner.sales.printpage');
        Route::post('sales-receive',[sales::class,'salesReceive'])->name('owner.sales.receive');
        Route::get('shop-data-get',[sales::class,'ShopDataGet'])->name('owner.get_shop');
        Route::get('area-data-get',[sales::class,'areaGet'])->name('owner.get_area');
        Route::get('area-wise-shop-data-get',[sales::class,'areaWiseShop'])->name('owner.get_area_shop');
        Route::get('get-shop-due',[sales::class,'getShopDue'])->name('owner.get_shop_due');
        Route::get('dsr-data-get',[sales::class,'DsrDataGet'])->name('owner.get_dsr');
        Route::get('supplier-product-data-get',[sales::class,'SupplierProduct'])->name('owner.get_supplier_product');
        Route::get('supplier-selected-product-data-get',[sales::class,'selectedSupplierProduct'])->name('owner.get_selected_supplier_product');
        Route::get('salesclosing-screen',[sales::class,'salesClosing'])->name('owner.salesClosing');
        Route::get('salesClosing-list',[sales::class,'salesClosingList'])->name('owner.salesClosingList');
        Route::post('salesclosing-data-get',[sales::class,'getSalesClosingData'])->name('owner.getSalesClosingData');
        Route::get('delivery-invoice/{id}',[sales::class,'deliveryInvoice'])->name('owner.delivery_invoice');

        //checklist
        Route::get('check-list',[sales::class,'getCheckList'])->name('owner.check_list');
        Route::get('check-list-bank',[sales::class,'getCheckBankList'])->name('owner.check_list_bank');
        Route::get('check-list-cash',[sales::class,'getCheckCashList'])->name('owner.check_list_cash');
        Route::get('check-list-due',[sales::class,'getCheckDueList'])->name('owner.check_list_due');
        Route::post('check-list-update',[sales::class,'checkStatusUpdate'])->name('owner.check_list_update');

        Route::resource('checkDetail',checkDetail::class,['as'=>'owner']);

        // Route::post('/owner/sales/update_product_price', [product::class, 'updateProductPrice'])->name('owner.update_product_price');
        Route::post('/owner/sales/update_product_price', [product::class, 'updateProductPrice'])->name('owner.update_product_price');


        // employee settings
        Route::resource('designation',designation::class,['as'=>'owner']);
        Route::resource('employee',employee::class,['as'=>'owner']);
        Route::resource('emLeave',emLeave::class,['as'=>'owner']);

        // Product
        Route::resource('category',category::class,['as'=>'owner']);
        Route::resource('group',group::class,['as'=>'owner']);
        Route::resource('product',product::class,['as'=>'owner']);
        Route::get('product_price',[product::class,'product_price'])->name('owner.product_price');
        Route::get('unit-pcs-get',[product::class,'UnitPcsGet'])->name('owner.unit_pcs_get');
        Route::post('/product/price/update', [product::class, 'updateProductPricesByDistribute'])->name('product_price_update');

        Route::resource('returnproduct',returnproduct::class,['as'=>'owner']);
        Route::resource('batch',batch::class,['as'=>'owner']);
        Route::resource('docontroll',docon::class,['as'=>'owner']);
        Route::get('do-selected-create',[docon::class,'selectedDoCreate'])->name('owner.do_selected_create');
        Route::get('dist-selected-product-data-get',[docon::class,'selectedDistProduct'])->name('owner.get_selected_dist_product');
        Route::get('doreceive',[docon::class,'DoRecive'])->name('owner.doreceive');
        Route::get('do-data-get',[docon::class,'doDataGet'])->name('owner.do_data_get');
        Route::get('unit-data-get',[docon::class,'UnitDataGet'])->name('owner.unit_data_get');
        Route::get('sales-unit-data-get',[sales::class,'UnitDataGet'])->name('owner.sales_unit_data_get');

        Route::post('doreceive', [docon::class,'DoRecive_edit'])->name('owner.do.accept_do_edit');
        Route::get('do-receive-list', [docon::class,'doReceiveList'])->name('owner.do.receivelist');
        Route::get('do-receive-list/{chalan_no}', [docon::class,'showDoReceive'])->name('owner.showDoReceive');
        Route::get('/receive-edit/{unikey}', [docon::class, 'getReceiveEdit'])->name('owner.get_rec_edit');
        Route::post('doreceive-update', [docon::class,'DoRecive_update'])->name('owner.do.accept_do_update');
        // Route::post('product-up-for-do', [docon::class, 'productUp'])->name('doscreenProductUp');
        Route::get('doscreenProductUp',[docon::class,'productUpdate'])->name('owner.doscreenProductUp');
        Route::get('get-product-data-ajax',[docon::class,'getProductData'])->name('owner.get_ajax_productdata');
        //return product
        Route::get('get-return-product',[returnproduct::class,'getReturnProduct'])->name('owner.get_return_product');
        Route::get('get-return-product-unit',[returnproduct::class,'UnitDataGet'])->name('owner.get_return_product_unit');
        Route::get('get-return-receive/{id}',[returnproduct::class,'returnReceive'])->name('owner.get_return_receive');
        Route::post('receive-rp-store/{id}',[returnproduct::class,'receiveRp'])->name('owner.receive_rp');
        Route::get('get-return-receive-partial/{id}',[returnproduct::class,'partialReceive'])->name('owner.get_return_receive_partial');
        Route::post('receive-rp-update/{id}',[returnproduct::class,'receiveRpUpdate'])->name('owner.receive_rp_update');
        Route::get('get-return-closing',[returnproduct::class,'closingIndex'])->name('owner.get_return_closing_index');
        Route::get('get-return-closing-show/{id}',[returnproduct::class,'closingShow'])->name('owner.return_closing_show');
        //report
        Route::get('/stock-report',[report::class,'stockreport'])->name('owner.sreport');
        Route::get('/stock-report-individual/{id}',[report::class,'stockindividual'])->name('owner.stock.individual');
        Route::get('/demage-report-individual/{id}',[report::class,'demageindividual'])->name('owner.demage.individual');
        Route::get('/shop-due-report',[report::class,'ShopDue'])->name('owner.shopdue');
        Route::get('/shop-balance-history/{id}',[report::class,'ShopBalanceHistory'])->name('owner.shop_balance_history');

        Route::get('/shop-balance-history-two/{id}',[report::class,'ShopBalanceHistoryTwo'])->name('owner.shop_balance_history_two');

        Route::get('undeliverd-report', [report::class,'undeliverdProduct'])->name('owner.undeliverd');
        Route::get('/sr-report',[report::class,'SRreport'])->name('owner.srreport');
        Route::get('/sr-report-product',[report::class,'srreportProduct'])->name('owner.srreportProduct');
        Route::get('/cash-collection-report',[report::class,'cashCollection'])->name('owner.cashCollection');
        Route::get('/damage-product-list',[report::class,'damageProductList'])->name('owner.damageProductList');
        Route::get('/dsr-salary',[report::class,'dsrsalary'])->name('owner.dsr_salary');
        Route::get('/sales-expense',[report::class,'expense'])->name('owner.sales_expense');
        Route::get('/sales-commission',[report::class,'salesCommission'])->name('owner.sales_commission');
        Route::get('/sales-summary-report',[report::class,'saleSummaryReport'])->name('owner.sales_summary_report');
        Route::get('/sales-report',[report::class,'salesReport'])->name('owner.sales_report');

        //Accounts
        Route::resource('master',master::class,['as'=>'owner']);
        Route::resource('sub_head',sub_head::class,['as'=>'owner']);
        Route::resource('child_one',child_one::class,['as'=>'owner']);
        Route::resource('child_two',child_two::class,['as'=>'owner']);
        Route::resource('navigate',navigate::class,['as'=>'owner']);

        Route::get('incomeStatement',[statement::class,'index'])->name('owner.incomeStatement');
        Route::get('incomeStatement_details',[statement::class,'details'])->name('owner.incomeStatement.details');

        //Voucher
        Route::resource('credit',credit::class,['as'=>'owner']);
        Route::resource('debit',debit::class,['as'=>'owner']);
        Route::get('get_head', [credit::class, 'get_head'])->name('owner.get_head');
        Route::resource('journal',journal::class,['as'=>'owner']);
        Route::get('journal_get_head', [journal::class, 'get_head'])->name('owner.journal_get_head');

    });
});

Route::group(['middleware'=>isManager::class],function(){
    Route::prefix('manager')->group(function(){
        Route::get('/dashboard', [dash::class,'managerDashboard'])->name('manager.dashboard');

        Route::resource('product',product::class,['as'=>'manager']);
        Route::get('unit-pcs-get',[product::class,'UnitPcsGet'])->name('manager.unit_pcs_get');
        Route::resource('docontroll',docon::class,['as'=>'manager']);
        Route::get('doreceive',[docon::class,'DoRecive'])->name('manager.doreceive');
        Route::get('do-data-get',[docon::class,'doDataGet'])->name('manager.do_data_get');
        Route::get('unit-data-get',[docon::class,'UnitDataGet'])->name('manager.unit_data_get');
        Route::get('sales-unit-data-get',[sales::class,'UnitDataGet'])->name('manager.sales_unit_data_get');
        Route::post('doreceive', [docon::class,'DoRecive_edit'])->name('manager.do.accept_do_edit');
        // Route::post('product-up-for-do', [docon::class, 'productUp'])->name('doscreenProductUp');
        Route::get('doscreenProductUp',[docon::class,'productUpdate'])->name('manager.doscreenProductUp');
        Route::get('get-product-data-ajax',[docon::class,'getProductData'])->name('manager.get_ajax_productdata');

    });
});

Route::group(['middleware'=>isJso::class],function(){
    Route::prefix('SR')->group(function(){
        Route::get('/dashboard', [dash::class,'jsoDashboard'])->name('SR.dashboard');
        Route::get('/sr-report',[report::class,'SRreport'])->name('SR.srreport');

    });
});

Route::group(['middleware'=>isSalesrepresentative::class],function(){
    Route::prefix('DSR')->group(function(){
        Route::get('/dashboard', [dash::class,'salesrepresentativeDashboard'])->name('DSR.dashboard');

    });
});

Route::group(['middleware'=>isAccountant::class],function(){
    Route::prefix('accountant')->group(function(){
        Route::get('/dashboard', [dash::class,'accountantDashboard'])->name('accountant.dashboard');

    });
});

Route::get('/sales/get-shops', [sales::class, 'getUpdatedShops'])->name('sales.getUpdatedShops');


