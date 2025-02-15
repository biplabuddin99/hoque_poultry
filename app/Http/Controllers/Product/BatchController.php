<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;

use App\Models\Product\Batch;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use Exception;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Batch::where(company())->paginate(10);
        return view('product.batch.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.batch.create');
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
            $data=new Batch;
            $data->name = $request->name;
            $data->dp = $request->dp;
            $data->tp = $request->tp;
            $data->mrp = $request->mrp;

            
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.batch.index');
            }
        }
        catch (Exception $e){
            // dd($e);
            Toastr::warning('Please try Again!');
            return back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $batch = Batch::findOrFail(encryptor('decrypt',$id));
        return view('product.batch.edit',compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $data= Batch::findOrFail(encryptor('decrypt',$id));
            $data->name = $request->name;
            $data->dp = $request->dp;
            $data->tp = $request->tp;
            $data->mrp = $request->mrp;

            
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
            Toastr::success('Updated Successfully!');
            return redirect()->route(currentUser().'.batch.index');
            }
        }
        catch (Exception $e){
            // dd($e);
            Toastr::warning('Please try Again!');
            return back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        //
    }
}
