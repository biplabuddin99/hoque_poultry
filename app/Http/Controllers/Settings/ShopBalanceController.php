<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\ShopBalance;
use App\Models\Settings\Supplier;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\Settings\Location\Area;
use App\Models\Settings\Shop;
use App\Models\Settings\ShopCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class ShopBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shops= ShopBalance::where('status',0)->where('cash_type',3)->orderBy('id','DESC');
        $users=User::with('role')->get();
        $srUsers=User::where('role_id',5)->get();
        $area= Area::select('id','name')->get();
        if($request->shop_id)
            $shops=$shops->where('shop_id',$request->shop_id);
        if($request->collection_by)
            $shops=$shops->where('collection_by',$request->collection_by);
        if($request->sr_id)
            $shops=$shops->where('sr_id',$request->sr_id);
        if($request->memu_no)
            $shops=$shops->where('reference_number','like','%'.$request->memu_no.'%');
        if ($request->distributor) {
            $shops=$shops->whereHas('shop.distributor', function($query) use ($request) {
                $query->where('id', $request->distributor);
            });
        }
        if ($request->area) {
            $shops=$shops->whereHas('shop.area', function($query) use ($request) {
                $query->where('id', $request->area);
            });
        }
        if ($request->shop_name) {
            $shops=$shops->whereHas('shop', function($query) use ($request) {
                $query->where('shop_name','like','%'.$request->shop_name.'%');
            });
        }
        $shops=$shops->paginate(50);
        $suppliers = Supplier::all();

        return view('settings.shopbalance.index',compact('shops','srUsers','users','area','suppliers'));
    }
    public function collectIndex(Request $request)
    {
        $shops= ShopBalance::orderBy('id','DESC');
        $users=User::with('role')->get();
        $area= Area::select('id','name')->get();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // if ($startDate && $endDate) {
        //     $shops = $shops->whereBetween('created_at', [$startDate, $endDate]);
        // }

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $shops->whereBetween(DB::raw('date(shop_balances.created_at)'), [$request->fdate, $tdate]);
        }
        if($request->shop_id)
            $shops=$shops->where('shop_id',$request->shop_id);
        if($request->collection_by)
            $shops=$shops->where('collection_by',$request->collection_by);
        if ($request->distributor) {
            $shops=$shops->whereHas('shop.distributor', function($query) use ($request) {
                $query->where('id', $request->distributor);
            });
        }
        if ($request->area) {
            $shops=$shops->whereHas('shop.area', function($query) use ($request) {
                $query->where('id', $request->area);
            });
        }
        if ($request->shop_name) {
            $shops=$shops->whereHas('shop', function($query) use ($request) {
                $query->where('shop_name','like','%'.$request->shop_name.'%');
            });
        }
        $shops=$shops->paginate(100);
        $suppliers = Supplier::all();

        return view('settings.shopbalance.collectIndex',compact('shops','users','suppliers','area','startDate','endDate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops=Shop::select('id','shop_name','owner_name')->get();
        $users=User::with('role')->get();
        return view('settings.shopbalance.create',compact('shops','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $shop=new ShopBalance;
            $shop->shop_id=$request->shop_id;
            $shop->balance_amount=$request->balance_amount;
            $shop->collection_by=$request->user_id;
            $shop->cash_type=$request->cash_type;
            if($shop->cash_type==0){
                $shop->old_due_date=$request->new_collect_date;
            }else{
                $shop->check_date=$request->new_collect_date;
                $shop->check_type=1;
                $shop->check_number = $request->check_number;
            }
            $shop->company_id=company()['company_id'];
            $shop->status=1;
            $shop->save();
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.shopbalance.index');
        }catch(Exception $e){
             //dd($e);
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function collectionByUpdate(Request $request){
        try{
            $check= ShopBalance::findOrFail($request->dueId);
            $check->collection_by=$request->user_id;
            if($request->collect_amount > 0){
                $receiveBalance= $request->collect_amount;
                // 1=check, 0=cash
                if($request->cash_type==1){
                    $check->check_collect_amount= $check->check_collect_amount+$receiveBalance;

                    $newDue = new ShopBalance;
                    $newDue->shop_id = $check->shop_id;
                    $newDue->sales_id = $check->sales_id;
                    $newDue->sr_id = $check->sr_id;
                    $newDue->reference_number = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((ShopBalance::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
                    $newDue->check_type= 1;
                    $newDue->cash_type= 1;
                    $newDue->check_number= $request->check_number;
                    $newDue->check_date= $request->collection_date;
                    $newDue->balance_amount = $receiveBalance;
                    $newDue->collect_amount = 0;
                    $newDue->collection_by = $request->user_id;
                    $newDue->status = 0;
                    $newDue->save();
                }else{
                    $check->collect_amount= $check->collect_amount+$receiveBalance;
                    $data = new ShopCollection;
                    $data->shop_id = $check->shop_id;
                    $data->shop_balance_id = $check->id;
                    $data->sales_id = $check->sales_id;
                    $data->reference_number = $check->reference_number;
                    $data->collect_amount = $receiveBalance;
                    $data->collection_date = now();
                    $data->collection_by = $request->user_id;
                    if($check->check_type == 1){
                        $data->cash_type = 1;
                        $data->check_date = $check->check_date;
                        $data->check_number = $check->check_number;
                        $data->status=1;
                    }else{
                        $data->cash_type = 0;
                        $data->status=1;
                    }
                    $data->save();
                }
            }
            if($check->save())
                Toastr::success('Collection User Updated Successfully');
                return back()->withInput();
        }catch(Exception $e){
            dd($e);
            Toastr::error('Please try again!');
            return back()->withInput();
        }
    }
    // public function collectionByUpdate(Request $request){
    //     try{
    //         $check= ShopBalance::findOrFail($request->dueId);
    //         $check->collection_by=$request->user_id;
    //         if($request->collect_amount > 0){
    //             // 1=check, 0=cash
    //             if($request->cash_type==1){
    //                 $currentBalance= $check->balance_amount-$check->collect_amount;
    //                 $receiveBalance= $request->collect_amount;
    //                 if($currentBalance==$receiveBalance){
    //                     $check->check_type= 1;
    //                     $check->cash_type= 1;
    //                     $check->check_number= $request->check_number;
    //                     $check->check_date= $request->collection_date;
    //                     $check->collect_amount = 0;
    //                     $check->collection_by = $request->user_id;
    //                     $check->status = 0;
    //                 }else{
    //                     $check->balance_amount=$currentBalance-$receiveBalance;
    //                     $check->collect_amount=0;
    //                     $newDue = new ShopBalance;
    //                     $newDue->shop_id = $check->shop_id;
    //                     $newDue->sales_id = $check->sales_id;
    //                     $newDue->sr_id = $check->sr_id;
    //                     $newDue->reference_number = $check->reference_number;
    //                     $newDue->check_type= 1;
    //                     $newDue->cash_type= 1;
    //                     $newDue->check_number= $request->check_number;
    //                     $newDue->check_date= $request->collection_date;
    //                     $newDue->balance_amount = $receiveBalance;
    //                     $newDue->collect_amount = 0;
    //                     $newDue->collection_by = $request->user_id;
    //                     $newDue->status = 0;
    //                     $newDue->save();
    //                 }
    //             }else{
    //                 $check->collect_amount= $check->collect_amount+$request->collect_amount;
    //                 $data = new ShopCollection;
    //                 $data->shop_id = $check->shop_id;
    //                 $data->sales_id = $check->sales_id;
    //                 $data->reference_number = $check->reference_number;
    //                 $data->collect_amount = $request->collect_amount;
    //                 $data->collection_date = $request->collection_date;
    //                 $data->collection_by = $request->user_id;
    //                 if($check->check_type == 1){
    //                     $data->cash_type = 1;
    //                     $data->check_date = $check->check_date;
    //                     $data->check_number = $check->check_number;
    //                     $data->status=1;
    //                 }else{
    //                     $data->cash_type = 0;
    //                     $data->status=1;
    //                 }
    //                 $data->save();
    //             }
    //         }
    //         if($check->save())
    //             Toastr::success('Collection User Updated Successfully');
    //             return back()->withInput();
    //     }catch(Exception $e){
    //         dd($e);
    //         Toastr::error('Please try again!');
    //         return back()->withInput();
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
