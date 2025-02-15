<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Customer;
use App\Models\Settings\Customer_balance;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\Settings\Supplier_balance;
use Exception;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers= Customer::where(company())->orderBy('id','DESC');
        if($request->name)
            $customers=$customers->where('name','like','%'.$request->name.'%');
        if($request->customer_code)
            $customers=$customers->where('customer_code','like','%'.$request->customer_code.'%');

        $customers=$customers->paginate(12);
        
        return view('settings.customer.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.customer.create');
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
            $data=new Customer;
            $data->customer_code = $request->customer_code;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->city = $request->city;
            $data->contact = $request->contact;
            $data->address = $request->address;
            
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
                if($request->balance > 0 ){
                    $supb= new Customer_balance;
                    $supb->customer_id = $data->id;
                    $supb->balance_date = now();
                    $supb->balance_amount = $request->balance;
                    $supb->status = 0;
                    $supb->company_id=company()['company_id'];
                    $supb->save();
                }

            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.customer.index');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerBalance(Request $request)
    {
        try {
            if ($request->balance > 0) {
                $data = new Customer_balance;
                $data->customer_id = $request->customer_id;
                $data->balance_date = now();
                $data->balance_amount = $request->balance;
                $data->status = 0;
                $data->company_id = company()['company_id'];
    
                if ($data->save()) {
                    Toastr::success('Balance Added Successfully!');
                    return redirect()->route(currentUser().'.customer.index');
                } else {
                    Toastr::warning('Please try Again!');
                    return redirect()->back();
                }
            } else {
                Toastr::warning('Balance should be greater than 0!');
                return redirect()->back();
            }
        } catch (Exception $e) {
            dd($e);
            return back()->withInput();
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail(encryptor('decrypt',$id));
        return view('settings.customer.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Customer::findOrFail(encryptor('decrypt',$id)); // Replace $customerId with the actual ID of the supplier you want to update
        
            if (!$data) {
                Toastr::error('Customer not found!');
                return redirect()->back();
            }
        
            $data->customer_code = $request->customer_code;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->city = $request->city;
            $data->contact = $request->contact;
            $data->address = $request->address;
        
            $data->company_id = company()['company_id'];
            $data->updated_by = currentUserId();
        
            if ($data->save()) {
                // Update customer balance if the balance is greater than 0
                // if ($request->balance > 0) {
                //     $supb = Customer_balance::where('customer_id', $data->id)->first();
        
                //     if (!$supb) {
                //         $supb = new Customer_balance;
                //         $supb->customer_id = $data->id;
                //         $supb->company_id = company()['company_id'];
                //     }
        
                //     $supb->balance_date = now();
                //     $supb->balance_amount = $request->balance;
                //     $supb->status = 1;
        
                //     $supb->save();
                // }
                // if ($request->balance == 0 || $request->balance == null) {
                    
                //     $supb= Customer_balance::where('customer_id',$data->id)->delete();
                // }
                // elseif ($request->balance > 0) {
                //     $supb = Customer_balance::where('customer_id', $data->id)->first();
        
                //     if (!$supb) {
                //         $supb = new Customer_balance;
                //         $supb->customer_id = $data->id;
                //         $supb->company_id = company()['company_id'];
                //     }
        
                //     $supb->balance_date = now();
                //     $supb->balance_amount = $request->balance;
                //     $supb->status = 0;
        
                //     $supb->save();
                // }
                Toastr::success('Update Successfully!');
                return redirect()->route(currentUser().'.customer.index');
            }
        } catch (Exception $e) {
            // dd($e);
            Toastr::warning('Please try Again!');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
