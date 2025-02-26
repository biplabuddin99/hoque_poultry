<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Shop;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\Settings\Location\Area;
use App\Models\Settings\ShopBalance;
use App\Models\Settings\Supplier;
use App\Models\User;
use Exception;
use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $distributor = Supplier::where(company())->get();
        $userSr=User::where(company())->where('role_id',5)->get();
        $shop= Shop::where(company())->orderBy('id','DESC');
        $area= Area::select('id','name')->get();
        if($request->shop_name)
            $shop=$shop->where('shop_name','like','%'.$request->shop_name.'%');
        if($request->owner_name)
            $shop=$shop->where('owner_name','like','%'.$request->owner_name.'%');
        if ($request->contact_no)
            $shop->where('contact',$request->contact_no);
        // if ($request->area)
        //     $shop->where('shops.area_name',$request->area);
        // if ($request->sr_id)
        //     $shop->where('shops.sr_id',$request->sr_id);

        $shop=$shop->paginate(20);

        return view('settings.shop.index',compact('shop','area','distributor','userSr'));
    }

    public function shopBalance(Request $request)
    {
        try {
            if ($request->balance > 0) {
                $data = new ShopBalance;
                $data->shop_id = $request->shop_id;
                $data->created_at = now();
                $data->balance_amount = $request->balance;
                $data->date = date('Y-m-d', strtotime($request->collect_date));
                //in=1 মানে টাকা কালেকশন বা জমা . out=0 মানে বকেয়া দেয়া
                $data->status = 1;
                $data->company_id = company()['company_id'];

                if ($data->save()) {
                    Toastr::success('সফলভাবে জমা নেয়া হয়েছে!');
                    return redirect()->route(currentUser().'.shop.index');
                } else {
                    Toastr::warning('আবার চেষ্টা করুন!');
                    return redirect()->back();
                }
            } else {
                Toastr::warning('আপনি ০ এর চেয়ে বেশি টাকা দিতে হবে!');
                return redirect()->back();
            }
        } catch (Exception $e) {
            dd($e);
            return back()->withInput();
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $area= Area::all();
        return view('settings.shop.create',compact('area'));
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
            $shop=new Shop;
            $shop->shop_name = $request->shop_name;
            $shop->owner_name = $request->owner_name;
            $shop->area_name = $request->area_name;
            $shop->dsr_id = $request->dsr_id;
            $shop->sr_id = $request->sr_id;
            $shop->sup_id = $request->sup_id;
            $shop->contact = $request->contact;
            $shop->address = $request->address;
            $shop->balance = $request->balance;
            $shop->status = 0;
            $shop->company_id=company()['company_id'];
            $shop->created_by= currentUserId();
            if($request->ajax()){
                if ($shop->save()) {
                        return response()->json(['shop' => 'Create Successfully!','shop_id' => $shop->id], 200);
                } else {
                    return response()->json(['error' => 'Failed to save jobhead.'], 500);
                }
                // $shop->save();
                // return response()->json($shop);
            }else{
                $shop->save();
                if($request->balance > 0){
                    $banance=new ShopBalance;
                    $banance->shop_id= $shop->id;
                    $banance->cash_type=0;
                    $banance->date = now();
                    $banance->balance_amount=$request->balance;
                    $banance->company_id=company()['company_id'];
                    // $banance->sr_id = $request->sr_id;
                    //in=1 মানে টাকা কালেকশন বা জমা . out=0 মানে বকেয়া দেয়া
                    $banance->status=0;
                    $banance->save();
                    }
                Toastr::success('Create Successfully!');
                return redirect()->route(currentUser().'.shop.index');
            }
            // $shop->save();
            // Toastr::success('Create Successfully!');
            // return redirect()->route(currentUser().'.shop.index');
        }
        catch (Exception $e){
             dd($e);
            return back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $area= Area::all();
        $shop = Shop::findOrFail(encryptor('decrypt',$id));
        return view('settings.shop.edit',compact('shop','area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $shop=Shop::findOrFail(encryptor('decrypt',$id));
            $shop->shop_name = $request->shop_name;
            $shop->owner_name = $request->owner_name;
            $shop->area_name = $request->area_name;
            $shop->dsr_id = $request->dsr_id;
            $shop->sr_id = $request->sr_id;
            $shop->sup_id = $request->sup_id;
            $shop->contact = $request->contact;
            $shop->address = $request->address;
            if($request->balance > 0){
                //cash_type 3= due, check_type 1= check
                $opb = ShopBalance::where('shop_id',$shop->id)->whereNull('sales_id')->where('cash_type',3)->whereNull('check_type')->firstOrNew();
                $opb->shop_id = $shop->id;
                $opb->balance_amount = $request->balance;
                $opb->reference_number = 'M-'.Carbon::now()->format('m-y').'-'. str_pad((ShopBalance::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
                $opb->new_due_date = $shop->created_at;
                $opb->cash_type = 3;
                $opb->status = 0;
                $opb->save();
            }
            $shop->balance = $request->balance;
            $shop->status = 0;
            $shop->company_id=company()['company_id'];
            $shop->created_by= currentUserId();
           $shop->save();
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.shop.index');
        }
        catch (Exception $e){
             dd($e);
            return back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop=Shop::findOrFail(encryptor('decrypt',$id));
        $shop->delete();
        return redirect()->route(currentUser().'.shop.index');
    }
}
