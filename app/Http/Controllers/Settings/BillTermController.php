<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Bill_term;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Exception;

class BillTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Bill_term::where(company())->paginate(10);
        return view('settings.bill.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.bill.create');
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
            $data=new Bill_term;
            $data->name = $request->bill;

            
            $data->company_id=company()['company_id'];

            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.bill.index');
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
     * @param  \App\Models\Settings\Bill_term  $bill_term
     * @return \Illuminate\Http\Response
     */
    public function show(Bill_term $bill_term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Bill_term  $bill_term
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bill = Bill_term::findOrFail(encryptor('decrypt',$id));
        return view('settings.bill.edit',compact('bill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Bill_term  $bill_term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= Bill_term::findOrFail(encryptor('decrypt',$id));
            $data->name = $request->bill;
            
            $data->company_id=company()['company_id'];

            if($data->save()){
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.bill.index');
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
     * @param  \App\Models\Settings\Bill_term  $bill_term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill_term $bill_term)
    {
        //
    }
}
