<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Models\Employee\designation;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use Exception;


class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = designation::where(company())->paginate(10);
        return view('employeeSettings.designation.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employeeSettings.designation.create');
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
            $data=new designation;
            $data->name = $request->designation;

            
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.designation.index');
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
     * @param  \App\Models\Employee\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designation = designation::findOrFail(encryptor('decrypt',$id));
        return view('employeeSettings.designation.edit',compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= designation::findOrFail(encryptor('decrypt',$id));
            $data->name = $request->designation;

            
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.designation.index');
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
     * @param  \App\Models\Employee\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data= designation::findOrFail(encryptor('decrypt',$id));
        $data->delete();
        return redirect()->back();
    }
}
