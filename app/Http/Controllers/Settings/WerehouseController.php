<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Werehouse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use Exception;

class WerehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Werehouse::where(company())->paginate(10);
        return view('settings.werehouse.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.werehouse.create');
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
            $data=new Werehouse;
            $data->name = $request->werehouse;
            $data->contact = $request->contact;
            $data->email = $request->email;
            $data->location = $request->location;
            $data->address = $request->address;

            
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.werehouse.index');
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
     * @param  \App\Models\Settings\Werehouse  $werehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Werehouse $werehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Werehouse  $werehouse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $werehouse = Werehouse::findOrFail(encryptor('decrypt',$id));
        return view('settings.werehouse.edit',compact('werehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Werehouse  $werehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= Werehouse::findOrFail(encryptor('decrypt',$id));
            $data->name = $request->werehouse;
            $data->contact = $request->contact;
            $data->email = $request->email;
            $data->location = $request->location;
            $data->address = $request->address;

            
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.werehouse.index');
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
     * @param  \App\Models\Settings\Werehouse  $werehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data= Werehouse::findOrFail(encryptor('decrypt',$id));
        $data->delete();
        return redirect()->back();
    }
}
