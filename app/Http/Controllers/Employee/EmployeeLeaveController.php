<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Models\Employee\employee_leave;
use App\Models\Employee\employee;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use Exception;

class EmployeeLeaveController extends Controller
{
    use ImageHandleTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = employee_leave::where(company())->paginate(10);
        return view('employeeSettings.employeeLeave.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = employee::where(company())->get();
        return view('employeeSettings.employeeLeave.create',compact('employee'));
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
            $data=new employee_leave;
            $data->employee_id = $request->employee_id;
            $data->leave_date_start = $request->leave_date_start;
            $data->leave_date_end = $request->leave_date_end;
            $data->leave_reason = $request->leave_reason;
            $data->application_details = $request->application_details;
            
            if($request->has('application_image'))
                $data->application_image=$this->resizeImage($request->application_image,'uploads/LeaveImage/'.company()['company_id'],true,200,200,false);

            $data->approve_by = currentUserId();
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.emLeave.index');
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
     * @param  \App\Models\employee_leave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function show(employee_leave $employee_leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\employee_leave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = employee::where(company())->get();
        $emdata = employee_leave::findOrFail(encryptor('decrypt',$id));
        return view('employeeSettings.employeeLeave.edit',compact('employee','emdata'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\employee_leave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= employee_leave::findOrFail(encryptor('decrypt',$id));
            $data->employee_id = $request->employee_id;
            $data->leave_date_start = $request->leave_date_start;
            $data->leave_date_end = $request->leave_date_end;
            $data->leave_reason = $request->leave_reason;
            $data->application_details = $request->application_details;

            if($request->has('application_image')){
                if($data->application_image){
                    if($this->deleteImage($data->application_image,'uploads/LeaveImage/'.company()['company_id'])){
                        $data->application_image=$this->resizeImage($request->application_image,'uploads/LeaveImage/'.company()['company_id'],true,200,200,false);
                    }
                }else{
                    $data->application_image=$this->resizeImage($request->application_image,'uploads/LeaveImage/'.company()['company_id'],true,200,200,false);
                }
            }

            $data->approve_by = currentUserId();
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
            Toastr::success('Updated Successfully!');
            return redirect()->route(currentUser().'.emLeave.index');
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
     * @param  \App\Models\employee_leave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data= employee_leave::findOrFail(encryptor('decrypt',$id));
        $data->delete();
        return redirect()->back();
    }
}
