<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

use App\Models\Sales\Sales;
use App\Models\Sales\SalesDetails;
use App\Models\Sales\TemporarySales;
use App\Models\Sales\TemporarySalesDetails;
use App\Models\Sales\SalesPayment;
use App\Models\Settings\Shop;
use App\Models\Settings\ShopBalance;
use App\Models\User;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\Product\Group;
use App\Models\Stock\Stock;
use \App\Models\Product\Product;
use App\Models\Sales\DisplayProduct;
use App\Models\Settings\DsrCashReceive;
use App\Models\Settings\Location\Area;
use App\Models\Settings\ShopCollection;
use App\Models\Settings\Supplier;
use App\Models\Settings\TempShopBalance;
use App\Models\Settings\Unit;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    public function index(Request $request)
    {
        $sales=TemporarySales::where('status',0)->where('sales_type',0)->where(company());
        $userSr=User::where(company())->where('role_id',5)->get();
        $shops = Shop::where(company())->select('id','shop_name','owner_name')->get();
        $products = Product::where(company())->select('id','product_name')->get();
        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(temporary_sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->shop_id)
            $sales->where('shop_id',$request->shop_id);
        if ($request->product_id)
            $sales->where('product_id',$request->product_id);

        $sales = $sales->paginate(20);
        return view('sales.index',compact('sales','userSr','products','shops'));
    }
    public function selectedIndex(Request $request)
    {
        $sales=TemporarySales::where('status',0)->where('sales_type',1)->orderBy('id','DESC');
        $userSr=User::where('role_id',5)->get();
        $distributors = Supplier::select('id','name')->get();
        $sr = User::where('role_id',5)->select('id','name')->get();
        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(temporary_sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->distributor_id)
            $sales->where('distributor_id',$request->distributor_id);
        if ($request->sr_id)
            $sales->where('sr_id',$request->sr_id);

        $sales = $sales->paginate(20);
        return view('sales.selectedIndex',compact('sales','userSr','sr','distributors'));
    }





    public function salesClosingList(Request $request)
    {
        $distributors = Supplier::where(company())->select('id','name')->get();
        $sr = User::where(company())->where('role_id',5)->select('id','name')->get();
        $sales = Sales::orderBy('id','DESC');
        // $sales = Sales::join('users', 'users.id', '=', 'sales.dsr_id')->where('sales.company_id', company())->select('sales.*', 'users.id', 'users.sr_id');
        $userSr=User::where(company())->where('role_id',5)->get();
        // if ($request->sr_id)
        // $sales->where('users.sr_id',$request->sr_id);

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->distributor_id)
            $sales->where('distributor_id',$request->distributor_id);
        if ($request->sr_id)
            $sales->where('sr_id',$request->sr_id);

        $sales = $sales->paginate(25);


        return view('sales.salesClosingList',compact('sales','userSr','distributors','sr'));
    }


    public function create()
    {
        $shops = Shop::all();
        $product = Product::where(company())->get();
        return view('sales.create',compact('shops','product'));
    }
    // public function create()
    // {
    //     $user=User::where('id',currentUserId())->where('role_id',3)->select('distributor_id')->first();
    //     $userSr=User::where(company())->where('role_id',5)->get();
    //     $area_name = Shop::select('area_name')->groupBy('area_name')->get();
    //     $shops = Shop::all();
    //     $userDsr=User::where(company())->where('role_id',4)->get();
    //     return view('sales.create',compact('user','userSr','shops','area_name','userDsr'));
    // }
    public function selectedCreate()
    {
        $user=User::where('id',currentUserId())->where('role_id',3)->select('distributor_id')->first();
        $shops = Shop::all();
        $area_name = Shop::select('area_name','sup_id')->orderBy('area_name')->groupBy('area_name')->groupBy('sup_id')->get();
        $userDsr=User::where(company())->where('role_id',4)->get();
        $userSr=User::where(company())->where('role_id',5)->get();
        return view('sales.selectedCreate',compact('user','userSr','userDsr','shops','area_name'));
    }

    public function store(Request $request)
    {
        try{
            $data=new TemporarySales;
            $data->sales_type = $request->sales_type;
            $data->product_pcs = $request->product_pcs;
            $data->shop_id = $request->shop_id;
            $data->product_id = $request->product_id;
            $data->product_price = $request->product_price;
            $data->kg = $request->kg;
            $data->gm = $request->gm;
            // $data->distributor_id = $request->distributor_id;
            $data->sales_date = date('Y-m-d', strtotime($request->sales_date));
            // $data->memu_code = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((TemporarySales::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $data->total = $request->total_taka;
            $data->status = 0;
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();
            $data->save();
           if($data->save()){
            if($request->total_taka){
            $banance=new ShopBalance;
            $banance->sales_id=$data->id;
            $banance->shop_id= $request->shop_id;
            $banance->cash_type=0;
            $banance->date = date('Y-m-d', strtotime($request->sales_date));
            $banance->balance_amount=$request->total_taka;
            $banance->company_id=company()['company_id'];
            $banance->sr_id = $request->sr_id;
            //in=1 মানে টাকা কালেকশন বা জমা . out=0 মানে বকেয়া দেয়া
            $banance->status=0;
            $banance->save();
            }
                Toastr::success('Create Successfully!');
                return redirect()->route(currentUser().'.sales.index');
            }else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try{
    //         $data=new TemporarySales;
    //         $data->select_shop_dsr = $request->select_shop_dsr;
    //         $data->shop_id = $request->shop_id;
    //         $data->area_id = $request->area_id;
    //         $data->dsr_id = $request->dsr_id;
    //         $data->sr_id = $request->sr_id;
    //         $data->distributor_id = $request->distributor_id;
    //         $data->sales_date = date('Y-m-d', strtotime($request->sales_date));
    //         $data->memu_code = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((TemporarySales::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
    //         if($request->receive_amount){
    //             $data->receive_amount = $request->receive_amount;
    //         }
    //         $data->total = $request->total;
    //         $data->status = 0;
    //         $data->sales_type = $request->sales_type;
    //         $data->company_id=company()['company_id'];
    //         $data->created_by= currentUserId();
    //         if($data->save()){
    //             if($request->subtotal_price){
    //                 foreach($request->subtotal_price as $key => $value){
    //                     if($request->subtotal_price[$key] > 0){
    //                         $details = new TemporarySalesDetails;
    //                         $details->tem_sales_id=$data->id;
    //                         if($request->group_id){
    //                             $details->group_id=$request->group_id[$key];
    //                         }
    //                         $details->product_id=$request->product_id[$key];
    //                         $details->ctn=$request->ctn[$key];
    //                         $details->pcs=$request->pcs[$key];
    //                         $details->select_tp_tpfree=$request->select_tp_tpfree[$key];
    //                         $details->pcs_price=$request->per_pcs_price[$key];
    //                         $details->ctn_price=$request->ctn_price[$key];
    //                         $details->totalquantity_pcs=$request->totalquantity_pcs[$key];
    //                         $details->subtotal_price=$request->subtotal_price[$key];
    //                         $details->company_id=company()['company_id'];
    //                         $details->created_by= currentUserId();
    //                         if($details->save()){
    //                             $stock=new Stock;
    //                             $stock->tem_sales_id=$data->id;
    //                             if($request->group_id){
    //                                 $stock->group_id=$request->group_id[$key];
    //                             }
    //                             $stock->product_id=$request->product_id[$key];
    //                             $stock->totalquantity_pcs=$request->totalquantity_pcs[$key];
    //                             $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
    //                             $stock->status_history=0;
    //                             $stock->status=0;
    //                             if($request->select_tp_tpfree[$key]==1){
    //                                 $stock->tp_price=$request->per_pcs_price[$key];
    //                             }else{
    //                                 $stock->tp_free=$request->per_pcs_price[$key];
    //                             }
    //                             $stock->save();
    //                         }
    //                     }
    //                 }
    //             }
    //             if($request->due_amount){
    //                 foreach($request->due_amount as $key => $value){
    //                     if($request->due_amount[$key] > 0){
    //                         $shopb = new TempShopBalance;
    //                         $shopb->tem_sales_id=$data->id;
    //                         $shopb->old_due_shop_id=$request->old_due_shop_id[$key];
    //                         $shopb->due_amount=$request->due_amount[$key];
    //                         $shopb->save();
    //                     }
    //                 }
    //             }
    //             DB::commit();
    //             if($request->sales_type==0){
    //                 Toastr::success('Create Successfully!');
    //                 return redirect()->route(currentUser().'.sales.index');
    //             }else{
    //                 Toastr::success('Create Successfully!');
    //                 return redirect()->route(currentUser().'.selectedIndex');
    //             }
    //         } else{
    //         Toastr::warning('Please try Again!');
    //          return redirect()->back();
    //         }

    //     }
    //     catch (Exception $e){
    //         dd($e);
    //         DB::rollback();
    //         return back()->withInput();

    //     }
    // }


    public function show($id)
    {
        $sales=TemporarySales::findOrFail(encryptor('decrypt',$id));
        return view('sales.show',compact('sales'));
    }
    public function selectedShow($id)
    {
        $sales=TemporarySales::findOrFail(encryptor('decrypt',$id));
        return view('sales.selectedShow',compact('sales'));
    }
    public function deliveryInvoice($id)
    {
        $sales=TemporarySales::findOrFail(encryptor('decrypt',$id));
        $salesDetails=TemporarySalesDetails::where('tem_sales_id',$sales->id)->get();
        $shopBalance= TempShopBalance::where('tem_sales_id',$sales->id)->get();
        return view('sales.delivery-invoice',compact('sales','salesDetails','shopBalance'));
    }

    public function selectedEdit($id)
    {
        $sales = TemporarySales::findOrFail(encryptor('decrypt',$id));
        $salesDetails=TemporarySalesDetails::where('tem_sales_id',$sales->id)->get();
        $tempShpBalance = TempShopBalance::where('tem_sales_id',$sales->id)->get();
        $productGroup = Product::where('distributor_id',$sales->distributor_id)->pluck('group_id');
        $group = Group::whereIn('id',$productGroup)->get();
        $product = Product::where('distributor_id',$sales->distributor_id)->get();
        $user=User::where('id',currentUserId())->where('role_id',3)->select('distributor_id')->first();
        $shops = Shop::where('sup_id',$sales->distributor_id)->where('area_name',$sales->area_id)->orderBy('shop_name')->get();
        $area_shop = Shop::where('sr_id',$sales->sr_id)->pluck('area_name');
        $area_name = Area::select('id','name')->whereIn('id',$area_shop)->get();
        $userDsr=User::where('role_id',4)->where('distributor_id',$sales->distributor_id)->get();
        $userSr=User::where('role_id',5)->where('distributor_id',$sales->distributor_id)->get();
        return view('sales.selectedEdit',compact('sales','salesDetails','tempShpBalance','group','product','user','userSr','userDsr','shops','area_name'));
    }


    public function salesUpdate($id)
    {
        //$sales = TemporarySales::where('status',0)->findOrFail(encryptor('decrypt',$id));
        $sales = TemporarySales::findOrFail(encryptor('decrypt',$id));
        $shops = Shop::all();
        $product = Product::where(company())->get();
        return view('sales.edit',compact('sales','shops','product'));
    }
    public function salesUpdateStore( Request $request, $id)
    {
        try{
            $data=TemporarySales::findOrFail(encryptor('decrypt',$id));
            $data->sales_type = $request->sales_type;
            $data->product_pcs = $request->product_pcs;
            $data->shop_id = $request->shop_id;
            $data->product_id = $request->product_id;
            $data->product_price = $request->product_price;
            $data->kg = $request->kg;
            $data->gm = $request->gm;
            // $data->distributor_id = $request->distributor_id;
            $data->sales_date = date('Y-m-d', strtotime($request->sales_date));
            // $data->memu_code = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((TemporarySales::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $data->total = $request->total_taka;
            $data->status = 0;
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();
            $data->save();
           if($data->save()){
                Toastr::success('Update Successfully!');
                return redirect()->route(currentUser().'.sales.index');
            }else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }
    public function PrimaryUpdate($id)
    {
        //$sales = TemporarySales::where('status',0)->findOrFail(encryptor('decrypt',$id));
        $sales = TemporarySales::findOrFail(encryptor('decrypt',$id));
        $shops=Shop::all();
        $dsr=User::where('role_id',4)->get();
        $userSr=User::where(company())->where('role_id',5)->get();
        return view('sales.primary-update',compact('sales','shops','dsr','userSr'));
    }

    public function primaryStore(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data=TemporarySales::findOrFail(encryptor('decrypt',$id));
            $data->select_shop_dsr = $request->select_shop_dsr;
            $data->shop_id = $request->shop_id;
            $data->area_id = $request->area_id;
            $data->dsr_id = $request->dsr_id;
            $data->sr_id = $request->sr_id;
            $data->distributor_id = $request->distributor_id;
            $data->sales_date = date('Y-m-d', strtotime($request->sales_date));
            $data->total = $request->total;
            $data->status = 0;
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();
            if($data->save()){
                if($request->product_id){
                    $dl=TemporarySalesDetails::where('tem_sales_id',$data->id)->delete();
                    $dlstock=Stock::where('tem_sales_id',$data->id)->delete();
                    foreach($request->product_id as $key => $value){
                        if($request->subtotal_price[$key] > 0){
                            $details = new TemporarySalesDetails;
                            $details->tem_sales_id=$data->id;
                            if($request->group_id){
                                $details->group_id=$request->group_id[$key];
                            }
                            $details->product_id=$request->product_id[$key];
                            $details->ctn=$request->ctn[$key];
                            $details->pcs=$request->pcs[$key];
                            $details->select_tp_tpfree=$request->select_tp_tpfree[$key];
                            $details->ctn_price=$request->ctn_price[$key];
                            $details->pcs_price=$request->per_pcs_price[$key];
                            $details->totalquantity_pcs=$request->totalquantity_pcs[$key];
                            $details->subtotal_price=$request->subtotal_price[$key];
                            $details->company_id=company()['company_id'];
                            $details->updated_by= currentUserId();
                            if($details->save()){
                                $stock=new Stock;
                                $stock->tem_sales_id=$data->id;
                                if($request->group_id){
                                    $stock->group_id=$request->group_id[$key];
                                }
                                $stock->product_id=$request->product_id[$key];
                                $stock->totalquantity_pcs=$request->totalquantity_pcs[$key];
                                $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                $stock->status_history=0;
                                $stock->status=0;
                                if($request->select_tp_tpfree[$key]==1){
                                    $stock->tp_price=$request->per_pcs_price[$key];
                                }else{
                                    $stock->tp_free=$request->per_pcs_price[$key];
                                }
                                $stock->save();
                            }
                        }
                    }
                }
                DB::commit();
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.sales.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }
        }
        catch (Exception $e){
            DB::rollback();
            dd($e);
            return back()->withInput();

        }
    }

    public function edit($id)
    {
        $sales = TemporarySales::findOrFail(encryptor('decrypt',$id));
        $shops=Shop::all();
        $dsr=User::where('role_id',4)->get();
        return view('sales.edit',compact('sales','shops','dsr'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data= TemporarySales::findOrFail(encryptor('decrypt',$id));
            $data->select_shop_dsr = $request->select_shop_dsr;
            $data->shop_id = $request->shop_id;
            $data->area_id = $request->area_id;
            $data->dsr_id = $request->dsr_id;
            $data->sr_id = $request->sr_id;
            $data->distributor_id = $request->distributor_id;
            $data->sales_date = date('Y-m-d', strtotime($request->sales_date));
            if($request->receive_amount){
                $data->receive_amount = $request->receive_amount;
            }
            $data->total = $request->total;
            $data->status = 0;
            $data->sales_type = $request->sales_type;
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();
            if($data->save()){
                if($request->subtotal_price){
                    TemporarySalesDetails::where('tem_sales_id',$data->id)->delete();
                    Stock::where('tem_sales_id',$data->id)->delete();
                    foreach($request->subtotal_price as $key => $value){
                        if($request->subtotal_price[$key] > 0){
                            $details = new TemporarySalesDetails;
                            $details->tem_sales_id=$data->id;
                            if($request->group_id){
                                $details->group_id=$request->group_id[$key];
                            }
                            $details->product_id=$request->product_id[$key];
                            $details->ctn=$request->ctn[$key];
                            $details->pcs=$request->pcs[$key];
                            $details->select_tp_tpfree=$request->select_tp_tpfree[$key];
                            $details->pcs_price=$request->per_pcs_price[$key];
                            $details->ctn_price=$request->ctn_price[$key];
                            $details->totalquantity_pcs=$request->totalquantity_pcs[$key];
                            $details->subtotal_price=$request->subtotal_price[$key];
                            $details->company_id=company()['company_id'];
                            $details->created_by= currentUserId();
                            if($details->save()){
                                $stock=new Stock;
                                $stock->tem_sales_id=$data->id;
                                if($request->group_id){
                                    $stock->group_id=$request->group_id[$key];
                                }
                                $stock->product_id=$request->product_id[$key];
                                $stock->totalquantity_pcs=$request->totalquantity_pcs[$key];
                                $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                $stock->status_history=0;
                                $stock->status=0;
                                if($request->select_tp_tpfree[$key]==1){
                                    $stock->tp_price=$request->per_pcs_price[$key];
                                }else{
                                    $stock->tp_free=$request->per_pcs_price[$key];
                                }
                                $stock->save();
                            }
                        }
                    }
                }
                if($request->due_amount){
                    TempShopBalance::where('tem_sales_id',$data->id)->delete();
                    foreach($request->due_amount as $key => $value){
                        if($request->due_amount[$key] > 0){
                            $shopb = new TempShopBalance;
                            $shopb->tem_sales_id=$data->id;
                            $shopb->old_due_shop_id=$request->old_due_shop_id[$key];
                            $shopb->due_amount=$request->due_amount[$key];
                            $shopb->save();
                        }
                    }
                }
                DB::commit();
                if($request->sales_type==0){
                    Toastr::success('Create Successfully!');
                    return redirect()->route(currentUser().'.sales.index');
                }else{
                    Toastr::success('Create Successfully!');
                    return redirect()->route(currentUser().'.selectedIndex');
                }
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }
    public function selectedReceiveScreen($id)
    {
        $sales = TemporarySales::findOrFail(encryptor('decrypt',$id));
        $shops=Shop::where('area_name',$sales->area_id)->get();
        $shopDue = TempShopBalance::where('tem_sales_id',$sales->id)->get();
        $dsr=User::where('role_id',4)->get();
        $pgid=Product::where('distributor_id',$sales->distributor_id)->where(company())->pluck('group_id');
        $group= Group::whereIn('id',$pgid)->get();
        $product=Product::where('distributor_id',$sales->distributor_id)->where(company())->get();
        $area= Area::all();
        return view('sales.selectedSalesClosing',compact('sales','shops','shopDue','dsr','group','product','area'));
    }
    public function getUpdatedShops(Request $request)
    {
        try {
            $shops = Shop::where('area_name',$request->area_name)->where('sup_id',$request->sup_id)->with('area')->get();
            return response()->json(['shops' => $shops]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to fetch shops.'], 500);
        }
    }


    public function salesReceiveScreen($id)
    {
        $sales = TemporarySales::findOrFail(encryptor('decrypt',$id));
        $shops=Shop::where('sup_id',$sales->distributor_id)->get();
        $dsr=User::where('role_id',4)->get();
        $product=Product::where('distributor_id',$sales->distributor_id)->where(company())->get();
        return view('sales.salesClosing',compact('sales','shops','dsr','product'));
    }
    public function salesReceive(Request $request)
    {  //dd($request->all());
        try{
            $tmsales=TemporarySales::where('id',$request->tem_sales_id)->first();
            if($tmsales){
                $tmsales->status=1;
                if($tmsales->save()){
                    $sales =new Sales;
                    $sales->shop_id = $request->shop_id;
                    $sales->area_id = $request->area_id;
                    $sales->dsr_id = $request->dsr_id;
                    $sales->sr_id = $request->sr_id;
                    $sales->distributor_id = $request->distributor_id;
                    $sales->tem_sales_id = $request->tem_sales_id;
                    $sales->sales_date = date('Y-m-d', strtotime($request->sales_date));
                    $sales->memu_code = $tmsales->memu_code;

                    $sales->daily_total_taka = $request->daily_total_taka;
                    $sales->return_total_taka = $request->return_total_taka;
                    $sales->expenses = $request->expenses;
                    $sales->commission = $request->commission;
                    $sales->dsr_cash = $request->cash;
                    $sales->dsr_salary = $request->dsr_salary;
                    $sales->final_total = $request->final_total;
                    $sales->today_final_cash = $request->today_final_cash;
                    $sales->final_cash_extra = $request->final_cash_extra;
                    //$sales->total = $request->total;
                    $sales->status = 1;
                    $sales->company_id=company()['company_id'];
                    $sales->updated_by= currentUserId();
                    if($sales->save()){
                        if($request->product_id){
                            foreach($request->product_id as $key => $value){
                                if($value){
                                    $details = new SalesDetails;
                                    $details->sales_id=$sales->id;
                                    if($request->group_id){
                                        $details->group_id=$request->group_id[$key];
                                    }
                                    $details->product_id=$request->product_id[$key];
                                    $details->ctn=$request->ctn[$key];
                                    $details->pcs=$request->pcs[$key];
                                    $details->ctn_return=$request->ctn_return[$key];
                                    $details->pcs_return=$request->pcs_return[$key];
                                    $details->ctn_damage=$request->ctn_damage[$key];
                                    $details->pcs_damage=$request->pcs_damage[$key];
                                    // $details->ctn_price=$request->ctn_price[$key];
                                    if($request->price_type[$key]=="1"){
                                        $details->tp_price=$request->tp_price[$key];
                                    }else{
                                        $details->tp_free=$request->tp_price[$key];
                                    }
                                    $details->total_return_pcs=$request->total_return_pcs[$key];
                                    $details->total_damage_pcs=$request->total_damage_pcs[$key];
                                    $details->total_sales_pcs=$request->total_sales_pcs[$key];
                                    $details->subtotal_price=$request->subtotal_price[$key];
                                    // $details->total_taka=$request->total_taka[$key];
                                    // $details->select_tp_tpfree=$request->select_tp_tpfree[$key];
                                    $details->status=0;
                                    $details->company_id=company()['company_id'];
                                    $details->created_by= currentUserId();
                                    if($details->save()){
                                        if($request->ctn_return[$key] >0 || $request->pcs_return[$key]>0){
                                            $stock=new Stock;
                                            $stock->sales_id=$sales->id;
                                            if($request->group_id){
                                                $stock->group_id=$request->group_id[$key];
                                            }
                                            $stock->product_id=$request->product_id[$key];
                                            $stock->totalquantity_pcs=$request->total_return_pcs[$key];
                                            $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                            $stock->status_history=1;
                                            $stock->status=1;
                                            if($request->price_type[$key]=="1"){
                                                $stock->tp_price=$request->tp_price[$key];
                                            }else{
                                                $stock->tp_free=$request->tp_price[$key];
                                            }
                                            $stock->save();
                                        }
                                        if($request->ctn_damage[$key] >0 || $request->pcs_damage[$key]>0){
                                            $stock=new Stock;
                                            $stock->sales_id=$sales->id;
                                            if($request->group_id){
                                                $stock->group_id=$request->group_id[$key];
                                            }
                                            $stock->product_id=$request->product_id[$key];
                                            $stock->totalquantity_pcs=$request->total_damage_pcs[$key];
                                            $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                            $stock->status_history=2;
                                            $stock->status=1;
                                            if($request->price_type[$key]=="1"){
                                                $stock->tp_price=$request->tp_price[$key];
                                            }else{
                                                $stock->tp_free=$request->tp_price[$key];
                                            }
                                            $stock->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if($request->return_product_id){
                        foreach($request->return_product_id as $i=>$return_product_id){
                            if($return_product_id){
                                $rsales=new SalesDetails;
                                $rsales->sales_id=$sales->id;
                                if($request->group_id_rtn){
                                    $rsales->group_id=$request->group_id_rtn[$i];
                                }
                                $rsales->product_id=$return_product_id;
                                $rsales->ctn_return=$request->old_ctn_return[$i];
                                $rsales->pcs_return=$request->old_pcs_return[$i];
                                $rsales->ctn_damage=$request->old_ctn_damage[$i];
                                $rsales->pcs_damage=$request->old_pcs_damage[$i];
                                $rsales->tp_price=$request->old_pcs_price[$i];
                                $rsales->subtotal_price=$request->return_subtotal_price[$i];
                                $rsales->total_return_pcs=$request->old_total_return_pcs[$i];
                                $rsales->total_damage_pcs=$request->old_total_damage_pcs[$i];
                                // $rsales->balance_amount=$request->old_due_tk[$i];
                                $rsales->status=1;
                                if($rsales->save()){
                                    if($request->old_ctn_return[$i] >0 || $request->old_pcs_return[$i]>0){
                                        $stock=new Stock;
                                        $stock->sales_id=$sales->id;
                                        if($request->group_id_rtn){
                                            $stock->group_id=$request->group_id_rtn[$i];
                                        }
                                        $stock->product_id=$request->return_product_id[$i];
                                        $stock->totalquantity_pcs=$request->old_total_return_pcs[$i];
                                        $stock->tp_price=$request->old_pcs_price[$i];
                                        $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                        $stock->status_history=1;
                                        $stock->status=1;
                                        // if($request->select_tp_tpfree[$i]==1){
                                        //     $stock->tp_price=$request->per_pcs_price[$i];
                                        // }else{
                                        //     $stock->tp_free=$request->per_pcs_price[$i];
                                        // }
                                        $stock->save();
                                    }
                                    if($request->old_ctn_damage[$i] >0 || $request->old_pcs_damage[$i]>0){
                                        $stock=new Stock;
                                        $stock->sales_id=$sales->id;
                                        if($request->group_id_rtn){
                                            $stock->group_id=$request->group_id_rtn[$i];
                                        }
                                        $stock->product_id=$request->return_product_id[$i];
                                        $stock->totalquantity_pcs=$request->old_total_damage_pcs[$i];
                                        $stock->tp_price=$request->old_pcs_price[$i];
                                        $stock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                        $stock->status_history=2;
                                        $stock->status=1;
                                        // if($request->select_tp_tpfree[$i]==1){
                                        //     $stock->tp_price=$request->per_pcs_price[$i];
                                        // }else{
                                        //     $stock->tp_free=$request->per_pcs_price[$i];
                                        // }
                                        $stock->save();
                                    }
                                }
                            }
                        }
                    }
                    if($request->display_product_id){
                        foreach($request->display_product_id as $dis=>$display_product_id){
                            if($request->display_product_qty > 0){
                                $disp=new DisplayProduct;
                                $disp->sales_id=$sales->id;
                                $disp->product_id=$display_product_id;
                                $disp->date=date('Y-m-d', strtotime($request->sales_date));
                                $disp->group_id=$request->group_id_display_product[$dis];
                                $disp->shop_name=$request->display_shop_name[$dis];
                                $disp->quantity=$request->display_product_qty[$dis];
                                $disp->price=$request->display_product_price[$dis];
                                $disp->total_price=$request->display_total_price[$dis];
                                if($disp->save()){
                                    $dispStock= new Stock;
                                    $dispStock->stock_date=date('Y-m-d', strtotime($request->sales_date));
                                    $dispStock->display_product_id=$disp->id;
                                    $dispStock->product_id=$disp->product_id;
                                    $dispStock->tp_free=$disp->price;
                                    $dispStock->totalquantity_pcs=$disp->quantity;
                                    $dispStock->status=0;
                                    $dispStock->status_history=6;
                                    $dispStock->save();
                                }
                            }
                        }
                    }
                    if($request->old_due_shop_id){
                        foreach($request->old_due_shop_id as $i=>$old_due_shop_id){
                            if($old_due_shop_id){
                                $olddue=new ShopBalance;
                                $olddue->sales_id=$sales->id;
                                $olddue->shop_id=$old_due_shop_id;
                                $olddue->cash_type=0;
                                $olddue->balance_amount=$request->old_due_tk[$i];
                                $olddue->old_due_date=$request->old_due_date[$i];
                                $olddue->company_id=company()['company_id'];
                                $olddue->sr_id = $request->sr_id;
                                $olddue->status=1;
                                $olddue->save();
                            }
                        }
                    }
                    if($request->new_due_shop_id){
                        foreach($request->new_due_shop_id as $i=>$new_due_shop_id){
                            if($new_due_shop_id){
                                $newdue=new ShopBalance;
                                $newdue->sales_id=$sales->id;
                                $newdue->shop_id=$new_due_shop_id;
                                $newdue->reference_number = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((ShopBalance::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
                                $newdue->cash_type=3;
                                $newdue->balance_amount=$request->new_due_tk[$i];
                                $newdue->new_due_date=$request->new_due_date[$i];
                                $newdue->company_id=company()['company_id'];
                                $newdue->sr_id = $request->sr_id;
                                $newdue->status=0;
                                $newdue->save();
                            }
                        }
                    }
                    // if($request->new_receive_shop_id){
                    //     foreach($request->new_receive_shop_id as $i=>$new_receive_shop_id){
                    //         if($new_receive_shop_id){
                    //             $payment=new SalesPayment;
                    //             $payment->sales_id=$sales->id;
                    //             $payment->shop_id=$new_receive_shop_id;
                    //             $payment->amount=$request->new_receive_tk[$i];
                    //             $payment->cash_type=1;
                    //             $payment->save();
                    //         }
                    //     }
                    // }
                    if($request->check_shop_id){
                        // until make it cash it will be due
                        foreach($request->check_shop_id as $i=>$check_shop_id){
                            if($check_shop_id){
                                $pay=new ShopBalance;
                                $pay->sales_id=$sales->id;
                                $pay->shop_id=$check_shop_id;
                                $pay->reference_number = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((ShopBalance::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
                                $pay->balance_amount=$request->check_shop_tk[$i];
                                $pay->check_date=$request->check_date[$i];
                                $pay->check_number=$request->check_number[$i];
                                $pay->company_id=company()['company_id'];
                                $pay->sr_id = $request->sr_id;
                                $pay->check_type=1;
                                $pay->cash_type=1;
                                $pay->status=0;
                                $pay->save();
                            }
                        }
                    }

                }
                Toastr::success('Sales Closing Successfully Done!');
            }else{
                Toastr::error('Sales not generated before sales closing!');
            }

            return redirect(route(currentUser().'.sales.printpage',encryptor('encrypt',$sales->id)));
        }
        catch (Exception $e){
            dd($e);
            return back()->withInput();

        }
    }

    public function getCheckList(Request $request){
        $suppliers = Supplier::all();
        $users=User::with('role')->get();
        $selectedSupplier = $request->input('distributor');
        $data = ShopBalance::where('cash_type',1)->where('check_type',1);

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $data->whereBetween(DB::raw('date(shop_balances.updated_at)'), [$request->fdate, $tdate]);
        }
        if($request->distributor_id){
            $supplier = $request->distributor_id;
            $data=$data->whereHas('shop.distributor',function($q) use ($supplier){
                $q->where('id', $supplier);
            });
        }
        if ($request->collection_by)
            $data->where('collection_by',$request->collection_by);

        if($request->memu_no)
            $data=$data->where('reference_number','like','%'.$request->memu_no.'%');

        $data = $data->get();
        return view('check.index',compact('data','users','suppliers'));
    }
    public function checkStatusUpdate(Request $request){
        try{
            $check= ShopBalance::findOrFail($request->checkId);
            $check->cash_type=$request->check_type;
            $check->collection_by=$request->user_id;
            //check_type=0 means cash request form check list
            if($request->check_type ==0){
                //there collect_amount = main balance(balance_amount) - (if previously collected as check after dishoner the check)
                $check->collect_amount=0;
                $newCollect = new ShopCollection;
                $newCollect->shop_id = $check->shop_id;
                $newCollect->shop_balance_id = $check->id;
                $newCollect->sales_id = $check->sales_id;
                $newCollect->reference_number = $check->reference_number;
                //it is actual collection from this due, collect_amount = main balance(balance_amount) - (if previously collected as check & cash)
                $newCollect->collect_amount = $check->balance_amount -($check->collect_amount+$check->check_collect_amount);
                $newCollect->collection_date = $request->collection_date;
                $newCollect->collection_by = $request->user_id;
                $newCollect->cash_type = 1;
                $newCollect->check_date = $check->check_date;
                $newCollect->check_number = $check->check_number;
                $newCollect->status=1;
                $newCollect->save();
            }
            if($check->save())
                Toastr::success('Check Updated Successfully');
                return back()->withInput();
        }catch(Exception $e){
            dd($e);
            Toastr::error('Please try again!');
            return back()->withInput();
        }
    }
    public function getCheckBankList(Request $request){
        $suppliers = Supplier::all();
        $users=User::with('role')->get();
        $data = ShopBalance::where('cash_type',2)->where('check_type',1);

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $data->whereBetween(DB::raw('date(shop_balances.updated_at)'), [$request->fdate, $tdate]);
        }
        if($request->distributor_id){
            $supplier = $request->distributor_id;
            $data=$data->whereHas('shop.distributor',function($q) use ($supplier){
                $q->where('id', $supplier);
            });
        }
        if ($request->collection_by)
            $data->where('collection_by',$request->collection_by);

        if($request->memu_no)
            $data=$data->where('reference_number','like','%'.$request->memu_no.'%');

        $data = $data->get();
        return view('check.bank',compact('data','users','suppliers'));
    }
    public function getCheckCashList(Request $request){
        $suppliers = Supplier::all();
        $users=User::with('role')->get();
        $selectedSupplier = $request->input('distributor');
        $data = ShopBalance::where('cash_type',0)->where('check_type',1);

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $data->whereBetween(DB::raw('date(shop_balances.updated_at)'), [$request->fdate, $tdate]);
        }
        if($request->distributor_id){
            $supplier = $request->distributor_id;
            $data=$data->whereHas('shop.distributor',function($q) use ($supplier){
                $q->where('id', $supplier);
            });
        }
        if ($request->collection_by)
            $data->where('collection_by',$request->collection_by);

        if($request->memu_no)
            $data=$data->where('reference_number','like','%'.$request->memu_no.'%');

        $data = $data->get();
        return view('check.cash',compact('data','users','suppliers'));
    }
    public function getCheckDueList(Request $request){
        $suppliers = Supplier::all();
        $users=User::with('role')->get();
        $data = ShopBalance::where('cash_type',3)->where('check_type',1);
        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $data->whereBetween(DB::raw('date(shop_balances.updated_at)'), [$request->fdate, $tdate]);
        }
        if($request->distributor_id){
            $supplier = $request->distributor_id;
            $data=$data->whereHas('shop.distributor',function($q) use ($supplier){
                $q->where('id', $supplier);
            });
        }
        if ($request->collection_by)
            $data->where('collection_by',$request->collection_by);

        if($request->memu_no)
            $data=$data->where('reference_number','like','%'.$request->memu_no.'%');

        $data = $data->get();
        return view('check.due',compact('data','users','suppliers'));
    }

    public function printSalesClosing($id)
    {
        // $sales = Sales::findOrFail($id);
        $sales = Sales::findOrFail(encryptor('decrypt',$id));
        //return $sales;
        // $shops=Shop::all();
        // $dsr=User::where('role_id',4)->get();
        // $product=Product::where(company())->get();
       // return view('sales.printSalesClosing',compact('sales','shops','dsr','product'));
        return view('sales.printSalesClosing',compact('sales'));
    }


    public function destroy($id)
    {
        $data= TemporarySales::findOrFail(encryptor('decrypt',$id));
        $tdl=TemporarySalesDetails::where('tem_sales_id',$data->id)->delete();
        $sdl=Stock::where('tem_sales_id',$data->id)->delete();
        $data->delete();
        Toastr::error('Opps!! You Delete Permanently!!');
        return redirect()->back();
    }

    public function ShopDataGet(Request $request)
    {
        $shop=Shop::select('id','shop_name','area_name')->where('sup_id',$request->supplier_id)->get();
        $dsr=User::select('id','name')->where('role_id',4)->where('distributor_id',$request->supplier_id)->get();
        $sr=User::select('id','name')->where('role_id',5)->where('distributor_id',$request->supplier_id)->get();
        $response = [];
        $response[] = [
            'shop' => $shop,
            'dsr' => $dsr,
            'sr' => $sr,
        ];
        return response()->json($response, 200);
    }
    public function areaGet(Request $request)
    {
        $area_id= Shop::where('sr_id',$request->sruser_id)->pluck('area_name');
        $area=Area::whereIn('id',$area_id)->get(['id', 'name']);
        return response()->json($area,200);
    }
    public function areaWiseShop(Request $request)
    {
        $shop= Shop::where('area_name',$request->area_id)->orderBy('shop_name','ASC')->get(['id', 'shop_name']);
        return response()->json($shop,200);
    }
    public function getShopDue(Request $request)
    {
        $balanceIn= ShopBalance::where('shop_id',$request->shop_id)->where('status',1)->sum('balance_amount');
        $balanceOut= ShopBalance::where('shop_id',$request->shop_id)->where('status',0)->sum('balance_amount');
        $shopDue= $balanceIn - $balanceOut;
        return response()->json($shopDue,200);
    }
    public function DsrDataGet(Request $request)
    {
        $dsr=User::where('role_id',4)->get();
        return response()->json($dsr,200);
    }
    // public function SupplierProduct(Request $request)
    // {
    //     $product=Product::where('distributor_id',$request->supplier_id)->get();
    //     $showqty =\App\Models\Stock\Stock::whereIn('status', [1, 3])->where('product_id',$product->id)->sum('totalquantity_pcs') - \App\Models\Stock\Stock::whereIn('status', [0, 4, 5])->where('product_id',$product->id)->sum('totalquantity_pcs');
    //     return response()->json($product,200);
    // }
    public function SupplierProduct(Request $request)
    {
        $products = Product::where('distributor_id', $request->supplier_id)->get();
        $response = [];

        foreach ($products as $product) {
            $stockIn=Stock::whereIn('status_history', [1, 3])->where('product_id', $product->id)->sum('totalquantity_pcs');
            $stockout=Stock::whereIn('status_history', [0, 2, 4, 5])->where('product_id', $product->id)->sum('totalquantity_pcs');
            $totalFree=Stock::where('product_id', $product->id)->sum('quantity_free');
            $showqty =  (($stockIn+$totalFree)- $stockout);

            // Include product and showqty in the response
            $response[] = [
                'product' => $product,
                'showqty' => $showqty,
            ];
        }

        return response()->json($response, 200);
    }
    public function getproduct(Request $request)
    {
        $products = Product::where('id', $request->productId)->pluck('tp_price');
        return response()->json($products, 200);
    }
    public function selectedSupplierProduct(Request $request)
    {
        $products = Product::where('distributor_id', $request->supplier_id)->get();
        $pid = Product::where('distributor_id', $request->supplier_id)->pluck('group_id');
        $group= Group::whereIn('id',$pid)->get();
        $response = [];

        foreach ($products as $product) {
            $stockIn=Stock::whereIn('status_history', [1, 3])->where('product_id', $product->id)->sum('totalquantity_pcs');
            $stockout=Stock::whereIn('status_history', [0, 2, 4, 5,6])->where('product_id', $product->id)->sum('totalquantity_pcs');
            $totalFree=Stock::where('product_id', $product->id)->sum('quantity_free');
            $showqty =  (($stockIn+$totalFree)- $stockout);

            // Include product and showqty in the response
            $response[] = [
                'product' => $product,
                'showqty' => $showqty,
            ];
        }
        $res=['group'=>$group,'data'=>$response];

        return response()->json($res, 200);
    }

    public function salesClosing(Request $request)
    {
        $sales = TemporarySales::all();
        $shops=Shop::all();
        $dsr=User::where('role_id',4)->get();
        $product=Product::where(company())->get();
        return view('sales.getsalesClosing',compact('sales','shops','dsr','product'));
    }
    public function getSalesClosingData(Request $request)
    {
        //dd($request->all());
        if (($request->has('sales_date') && $request->has('dsr_id')) || ($request->has('sales_date') && $request->has('shop_id'))) {
            $sales = TemporarySales::where('status', 0)
                ->where(company())
                ->where('sales_date', date('Y-m-d', strtotime($request->sales_date)))
                ->where(function ($query) use ($request) {
                    $query->where('dsr_id', $request->dsr_id)
                        ->orWhere('shop_id', $request->shop_id);
                })
                ->orderBy('id', 'asc')
                ->first();

            if ($sales) {
                $shops = Shop::where('sup_id', $sales->distributor_id)->get();
                $dsr = User::where('role_id', 4)->get();
                $userSr = User::where(company())->where('role_id', 5)->get();
                $product = Product::where('distributor_id', $sales->distributor_id)->where(company())->get();

                return view('sales.salesClosingSidebar', compact('sales', 'shops', 'dsr', 'product', 'userSr'));
            } else {
                return view('sales.nodata');
            }
        } else {
            return redirect()->back();
        }
    }

    // public function UnitDataGet(Request $request)
    // {
    //     $productId=$request->product_id;
    //     $unitStyleId=Product::where('id', $productId)->where('status',0)->pluck('unit_style_id');
    //     $unit=Unit::whereIn('unit_style_id', $unitStyleId)->pluck('qty');
    //     $showqty = \App\Models\Stock\Stock::whereIn('status', [1, 3])->where('product_id', $product->id)->sum('totalquantity_pcs') - \App\Models\Stock\Stock::whereIn('status', [0, 4, 5])->where('product_id', $product->id)->sum('totalquantity_pcs');
    //     return response()->json($unit,200);
    // }
    public function UnitDataGet(Request $request)
    {
        $productId = $request->product_id;
        // Retrieve a single product based on the provided $productId
        $product = Product::where('id', $productId)->where('status', 0)->first();

        // Check if a product is found
        if ($product) {
            $unitStyleId = $product->unit_style_id;
            $unit = Unit::where('unit_style_id', $unitStyleId)->pluck('qty');
                $stockIn=Stock::whereIn('status_history', [1, 3])->where('product_id', $product->id)->sum('totalquantity_pcs');
                $stockout=Stock::whereIn('status_history', [0, 2, 4, 5 ,6])->where('product_id', $product->id)->sum('totalquantity_pcs');
                $totalFree=Stock::where('product_id', $product->id)->sum('quantity_free');
                $showqty =  (($stockIn+$totalFree)- $stockout);
                return response()->json([
                    'unit' => $unit,
                    'showqty' => $showqty,
                ], 200);
        } else {
            // Handle the case where no product is found
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

}
