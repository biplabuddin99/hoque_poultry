<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Supplier;
use App\Models\Settings\Customer_balance;
use App\Models\Settings\Bill_term;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\Settings\Supplier_balance;
use Exception;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers= Supplier::where(company())->orderBy('id','DESC');
        if($request->name)
            $suppliers=$suppliers->where('name','like','%'.$request->name.'%');
        if($request->supplier_code)
            $suppliers=$suppliers->where('supplier_code','like','%'.$request->supplier_code.'%');

        $suppliers=$suppliers->paginate(12);
        $bill_term = Bill_term::get();
        return view('settings.supplier.index',compact('suppliers','bill_term'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.supplier.create');
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
            $data=new Supplier;
            $data->supplier_code = $request->supplier_code;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->city = $request->city;
            $data->contact = $request->contact;
            $data->address = $request->address;

            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
                if($request->balance){
                    $supb= new Supplier_balance;
                    $supb->supplier_id = $data->id;
                    $supb->balance_date = now();
                    $supb->balance_amount = $request->balance;
                    $supb->status = 1;
                    $supb->company_id=company()['company_id'];
                    $supb->save();
                }

            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.supplier.index');
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
    public function supplierBalance(Request $request)
    {
        try {
            if ($request->balance > 0) {
                $data = new Supplier_balance;
                $data->supplier_id = $request->supplier_id;
               // $data->balance_date = now();
                $data->balance_amount = $request->balance;
                $data->reference_number = $request->reference_number;
                $data->balance_date = $request->balance_date;
                $data->billterm_id = $request->billterm_id;
                $data->status = 1;
                $data->company_id = company()['company_id'];

                if ($data->save()) {
                    Toastr::success('Balance Added Successfully!');
                    return redirect()->route(currentUser().'.supplier.index');
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
     * @param  \App\Models\Settings\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $suplier=Supplier_balance::where('supplier_id',encryptor('decrypt',$id));
        $supplierman=Supplier_balance::where('supplier_id',encryptor('decrypt',$id))->groupBy('supplier_id')->first();
        if ($request->fdate){
            $tdate = $request->tdate ?: $request->fdate;
            $suplier->whereBetween(DB::raw('date(supplier_balances.balance_date)'), [$request->fdate, $tdate]);
        }
        $suplier=$suplier->get();
        return view('settings.supplier.show',compact('suplier','supplierman'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail(encryptor('decrypt',$id));
        return view('settings.supplier.edit',compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Supplier::findOrFail(encryptor('decrypt',$id)); // Replace $supplierId with the actual ID of the supplier you want to update

            if (!$data) {
                Toastr::error('Supplier not found!');
                return redirect()->back();
            }

            $data->supplier_code = $request->supplier_code;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->city = $request->city;
            $data->contact = $request->contact;
            $data->address = $request->address;

            $data->company_id = company()['company_id'];
            $data->updated_by = currentUserId();

            if ($data->save()) {
                Toastr::success('Update Successfully!');
                return redirect()->route(currentUser().'.supplier.index');
            }
        } catch (Exception $e) {
            Toastr::warning('Please try Again!');
            return back()->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data= Supplier_balance::findOrFail(encryptor('decrypt',$id));
        $data->delete();
        return redirect()->back();
    }
}
