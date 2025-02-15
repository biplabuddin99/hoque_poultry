<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Unit;
use App\Models\Settings\Unit_style;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use Exception;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $units=Unit::orderBy('id','asc');
        if($request->name)
            $units=$units->where('name','like','%'.$request->name.'%');

        $units=$units->paginate(12);
        return view('settings.unit.index',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unitstyles= Unit_style::orderBy('name')->get();
        return view('settings.unit.create',compact('unitstyles'));
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
            $data=new Unit;
            $data->unit_style_id=$request->unit_style_id;
            $data->name=$request->name;
            $data->qty=$request->qty;
            $data->status=1;
            $data->created_by= currentUserId();

            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.unit.index');
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
     * @param  \App\Models\Settings\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unitstyles= Unit_style::all();
        $unit= Unit::findOrFail(encryptor('decrypt',$id));
        return view('settings.unit.edit',compact('unit','unitstyles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= Unit::findOrFail(encryptor('decrypt',$id));
            $data->unit_style_id=$request->unit_style_id;
            $data->name=$request->name;
            $data->qty=$request->qty;
            $data->status=$request->status;

            $data->updated_by= currentUserId();

            if($data->save()){
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.unit.index');
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
     * @param  \App\Models\Settings\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        //
    }
}
