<?php

namespace App\Http\Controllers\Settings\Location;

use App\Http\Controllers\Controller;

use App\Models\Settings\Location\Area;
use App\Models\Settings\Supplier;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Exception;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Area::all();
        return view('settings.location.area.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $distributor=Supplier::all();
        return view('settings.location.area.create',compact('distributor'));
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
            $area=new Area;
            $area->distributor_id=$request->distributor_id;
            $area->name=$request->name;
            if($area->save()){
                Toastr::success('Create Successfully!');
                return redirect()->route(currentUser().'.area.index');
            }else
                Toastr::warning('Please try Again!');
                return redirect()->back();   
        }catch(Exception $e){
            dd($e);
            Toastr::warning('Please try Again!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $distributor=Supplier::all();
        $area=Area::findOrFail(encryptor('decrypt',$id));
        return view('settings.location.area.edit',compact('distributor','area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try{
            $area= Area::findOrFail(encryptor('decrypt',$id));
            $area->distributor_id=$request->distributor_id;
            $area->name=$request->name;
            if($area->save()){
                Toastr::success('Update Successfully!');
                return redirect()->route(currentUser().'.area.index');
            }else
                Toastr::warning('Please try Again!');
                return redirect()->back();   
        }catch(Exception $e){
            dd($e);
            Toastr::warning('Please try Again!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        //
    }
}
