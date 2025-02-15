<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

use App\Models\Sales\CheckDetails;
use App\Models\Settings\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use Exception;

class CheckDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data= CheckDetails::orderBy('id','DESC');
        $sr= User::where(company())->where('role_id',5)->get();

        if($request->check_no)
        $data->where('check_number',$request->check_no);

        if($request->check_date)
        $data->where('check_date',$request->check_date);

        if($request->sr_id)
        $data->where('sr_id',$request->sr_id);

        if($request->memu_no)
        $data->where('memu_number','like','%'.$request->memu_no.'%');

        $data = $data->paginate(20);
        return view('checkDetail.index',compact('data','sr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::select('id','shop_name')->get();
        $sr= User::where(company())->where('role_id',5)->get();
        return view('checkDetail.create',compact('shop','sr'));
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
            $data=new CheckDetails;
            $data->shop_id = $request->shop_id;
            $data->sr_id = $request->sr_id;
            $data->bank_name = $request->bank_name;
            $data->check_number = $request->check_number;
            $data->check_date = $request->check_date;
            $data->cash_date = $request->cash_date;
            $data->check_status = $request->check_status;
            $data->memu_number = $request->memu_number;
            $data->amount = $request->amount;
            $data->collected_amount = $request->collected_amount;
            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.checkDetail.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            // dd($e);
            return back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales\CheckDetails  $checkDetails
     * @return \Illuminate\Http\Response
     */
    public function show(CheckDetails $checkDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales\CheckDetails  $checkDetails
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = CheckDetails::findOrFail(encryptor('decrypt',$id));
        $shop = Shop::select('id','shop_name')->get();
        $sr= User::where(company())->where('role_id',5)->get();
        return view('checkDetail.edit',compact('data','shop','sr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales\CheckDetails  $checkDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= CheckDetails::findOrFail(encryptor('decrypt',$id));
            $data->shop_id = $request->shop_id;
            $data->sr_id = $request->sr_id;
            $data->bank_name = $request->bank_name;
            $data->check_number = $request->check_number;
            $data->check_date = $request->check_date;
            $data->cash_date = $request->cash_date;
            $data->check_status = $request->check_status;
            $data->memu_number = $request->memu_number;
            $data->amount = $request->amount;
            $data->collected_amount = $request->collected_amount;
            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.checkDetail.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            // dd($e);
            return back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales\CheckDetails  $checkDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(CheckDetails $checkDetails)
    {
        //
    }
}
