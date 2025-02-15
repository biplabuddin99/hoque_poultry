<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ReturnProduct;
use App\Models\Product\ReturnProductDetails;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\Product\Group;
use App\Models\Product\Product;
use App\Models\Product\ReturnReceiveHistory;
use App\Models\Settings\Supplier;
use App\Models\Settings\Unit;
use App\Models\Stock\Stock;
use Exception;
use DB;

class ReturnProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $distributor= Supplier::select('id','name')->get();
        $data=ReturnProduct::whereNot('status',2)->orderBy('id','DESC');
        if($request->supplier_id){
            $data->where('return_products.distributor_id',$request->supplier_id);
        }
        if($request->invoice_number){
            $data->where('return_products.invoice_number',$request->invoice_number);
        }
        $data = $data->paginate(25);
        return view('product.returnproduct.index',compact('data','distributor'));
    }

    public function closingIndex(Request $request)
    {
        $distributor= Supplier::select('id','name')->get();
        $data=ReturnProduct::where('status',2)->orderBy('id','DESC');
        if($request->supplier_id){
            $data->where('return_products.distributor_id',$request->supplier_id);
        }
        if($request->invoice_number){
            $data->where('return_products.invoice_number',$request->invoice_number);
        }
        $data = $data->paginate(25);
        return view('product.returnproduct.closingIndex',compact('data','distributor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $distributor= Supplier::select('id','name')->get();
        return view('product.returnproduct.create',compact('distributor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $data=new ReturnProduct;
            $data->distributor_id = $request->distributor_id;
            $data->return_type = $request->return_type;
            $data->driver_name = $request->driver_name;
            $data->helper = $request->helper;
            $data->garir_number = $request->garir_number;
            $data->invoice_number = $request->invoice_number;
            $data->note = $request->note;
            $data->total = $request->grand_total;
            $data->status = 0;
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
                if($request->product_id){
                    foreach($request->product_id as $key => $value){
                        // dd($request->all());
                        if($value){
                            $details = new ReturnProductDetails;
                            $details->return_product_id=$data->id;
                            $details->group_id=$request->group_id[$key];
                            $details->product_id=$request->product_id[$key];
                            //$details->return_type=$request->return_type[$key];
                            $details->ctn_return=$request->ctn_return[$key];
                            $details->pcs_return=$request->pcs_return[$key];
                            $details->total_pcs_return=$request->total_pcs_return[$key];
                            $details->price=$request->dp_price[$key];
                            $details->amount=$request->subtotal_price[$key];
                            $details->save();
                        }
                    }
                    foreach($request->product_id as $key => $value){
                        // dd($request->all());
                        if($value){
                            $stock=new Stock;
                            $stock->return_id=$data->id;
                            $stock->chalan_no= $data->invoice_number;
                            $stock->stock_date= now();
                            $stock->distributor_id=$data->distributor_id;
                            $stock->group_id=$request->group_id[$key];
                            $stock->product_id=$request->product_id[$key];
                            $stock->totalquantity_pcs=$request->total_pcs_return[$key];
                            $stock->dp_pcs=$request->dp_price[$key];
                            $stock->subtotal_dp_pcs=$request->subtotal_price[$key];
                            $stock->status=0;
                            $stock->status_history=5;
                            //'0=out_sales,1=in_return,2=in_damage,3=in_purchase,4=out_damage,5=out_purchase_return'
                            $stock->company_id=company()['company_id'];
                            $stock->created_by= currentUserId();
                            $stock->save();
                        }
                    }
                    DB::commit();
                }
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.returnproduct.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\ReturnProduct  $returnProduct
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $return=ReturnProduct::findOrFail(encryptor('decrypt',$id));
        $returnDetails=ReturnProductDetails::where('return_product_id',$return->id)->get();
        return view('product.returnproduct.returnShow',compact('return','returnDetails'));
    }
    public function closingShow($id)
    {
        $return=ReturnProduct::findOrFail(encryptor('decrypt',$id));
        $returnDetails=ReturnReceiveHistory::where('return_product_id',$return->id)->get();
        return view('product.returnproduct.returnClosingShow',compact('return','returnDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product\ReturnProduct  $returnProduct
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $return = ReturnProduct::findOrFail(encryptor('decrypt',$id));
        $returnDetails=ReturnProductDetails::where('return_product_id',$return->id)->get();
        $distributor= Supplier::select('id','name')->get();
        $productGroup = Product::where('distributor_id',$return->distributor_id)->pluck('group_id');
        $group = Group::whereIn('id',$productGroup)->get();
        $product = Product::where('distributor_id',$return->distributor_id)->get();
        return view('product.returnproduct.edit',compact('return','returnDetails','distributor','group','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product\ReturnProduct  $returnProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data= ReturnProduct::findOrFail(encryptor('decrypt',$id));
            $data->distributor_id = $request->distributor_id;
            $data->return_type = $request->return_type;
            $data->driver_name = $request->driver_name;
            $data->helper = $request->helper;
            $data->garir_number = $request->garir_number;
            $data->invoice_number = $request->invoice_number;
            $data->note = $request->note;
            $data->total = $request->grand_total;
            $data->status = 0;
            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();

            if($data->save()){
                if($request->product_id){
                    ReturnProductDetails::where('return_product_id',$data->id)->delete();
                    Stock::where('return_id',$data->id)->where('status',0)->where('status_history',5)->delete();
                    foreach($request->product_id as $key => $value){
                        // dd($request->all());
                        if($value){
                            $details = new ReturnProductDetails;
                            $details->return_product_id=$data->id;
                            $details->group_id=$request->group_id[$key];
                            $details->product_id=$request->product_id[$key];
                            //$details->return_type=$request->return_type[$key];
                            $details->ctn_return=$request->ctn_return[$key];
                            $details->pcs_return=$request->pcs_return[$key];
                            $details->total_pcs_return=$request->total_pcs_return[$key];
                            $details->price=$request->dp_price[$key];
                            $details->amount=$request->subtotal_price[$key];
                            $details->save();
                        }
                    }
                    foreach($request->product_id as $key => $value){
                        // dd($request->all());
                        if($value){
                            $stock=new Stock;
                            $stock->return_id=$data->id;
                            $stock->chalan_no= $data->invoice_number;
                            $stock->stock_date= now();
                            $stock->distributor_id=$data->distributor_id;
                            $stock->group_id=$request->group_id[$key];
                            $stock->product_id=$request->product_id[$key];
                            $stock->totalquantity_pcs=$request->total_pcs_return[$key];
                            $stock->dp_pcs=$request->dp_price[$key];
                            $stock->subtotal_dp_pcs=$request->subtotal_price[$key];
                            $stock->status=0;
                            $stock->status_history=5;
                            //'0=out_sales,1=in_return,2=in_damage,3=in_purchase,4=out_damage,5=out_purchase_return'
                            $stock->updated_by= currentUserId();
                            $stock->save();
                        }
                    }
                    DB::commit();
                }
            Toastr::success('Update Successfully!');
            return redirect()->route(currentUser().'.returnproduct.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }

    public function returnReceive($id)
    {
        $return = ReturnProduct::findOrFail(encryptor('decrypt',$id));
        $returnDetails=ReturnProductDetails::where('return_product_id',$return->id)->get();
        $distributor= Supplier::select('id','name')->get();
        $productGroup = Product::where('distributor_id',$return->distributor_id)->pluck('group_id');
        $group = Group::whereIn('id',$productGroup)->get();
        $product = Product::where('distributor_id',$return->distributor_id)->get();
        return view('product.returnproduct.receive',compact('return','returnDetails','distributor','group','product'));
    }

    public function receiveRp(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data= ReturnProduct::findOrFail(encryptor('decrypt',$id));
            $data->status = $request->receive_type;
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
                if($request->product_id){
                    foreach($request->product_id as $key => $value){
                        // dd($request->all());
                        if($value){
                            $details = ReturnProductDetails::find($request->return_detail_id[$key]);
                            $details->total_receive_qty=$request->total_pcs_return[$key];
                            if($details->save()){
                                $reph = new ReturnReceiveHistory;
                                $reph->return_product_id=$data->id;
                                $reph->group_id=$request->group_id[$key];
                                $reph->product_id=$request->product_id[$key];
                                $reph->ctn_return=$request->ctn_return[$key];
                                $reph->pcs_return=$request->pcs_return[$key];
                                $reph->total_pcs_return=$request->total_pcs_return[$key];
                                $reph->price=$request->dp_price[$key];
                                $reph->amount=$request->subtotal_price[$key];
                                if($reph->save()){
                                    $stock=new Stock;
                                    $stock->return_id=$data->id;
                                    $stock->chalan_no= $data->invoice_number;
                                    $stock->stock_date= now();
                                    $stock->distributor_id=$data->distributor_id;
                                    $stock->group_id=$request->group_id[$key];
                                    $stock->product_id=$request->product_id[$key];
                                    $stock->totalquantity_pcs=$request->total_pcs_return[$key];
                                    $stock->dp_pcs=$request->dp_price[$key];
                                    $stock->subtotal_dp_pcs=$request->subtotal_price[$key];
                                    $stock->status=1;
                                    $stock->status_history=1;
                                    //'0=out_sales,1=in_return,2=in_damage,3=in_purchase,4=out_damage,5=out_purchase_return'
                                    $stock->created_by= currentUserId();
                                    $stock->save();
                                }
                            }
                        }
                    }
                    DB::commit();
                }
            Toastr::success('Receive Successfully!');
            return redirect()->route(currentUser().'.returnproduct.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }

    public function partialReceive($id)
    {
        $return = ReturnProduct::findOrFail(encryptor('decrypt',$id));
        $returnDetails=ReturnProductDetails::where('return_product_id',$return->id)->get();
        $distributor= Supplier::select('id','name')->get();
        $productGroup = Product::where('distributor_id',$return->distributor_id)->pluck('group_id');
        $group = Group::whereIn('id',$productGroup)->get();
        $product = Product::where('distributor_id',$return->distributor_id)->get();
        return view('product.returnproduct.receivePartial',compact('return','returnDetails','distributor','group','product'));
    }

    public function receiveRpUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data= ReturnProduct::findOrFail(encryptor('decrypt',$id));
            $data->status = $request->receive_type;
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
                if($request->product_id){
                    ReturnReceiveHistory::where('return_product_id',$data->id)->delete();
                    Stock::where('return_id',$data->id)->where('status',1)->where('status_history',1)->delete();
                    foreach($request->product_id as $key => $value){
                        // dd($request->all());
                        if($value){
                            $details = ReturnProductDetails::find($request->return_detail_id[$key]);
                            $details->total_receive_qty=$request->total_pcs_return[$key];
                            if($details->save()){
                                $reph = new ReturnReceiveHistory;
                                $reph->return_product_id=$data->id;
                                $reph->group_id=$request->group_id[$key];
                                $reph->product_id=$request->product_id[$key];
                                $reph->ctn_return=$request->ctn_return[$key];
                                $reph->pcs_return=$request->pcs_return[$key];
                                $reph->total_pcs_return=$request->total_pcs_return[$key];
                                $reph->price=$request->dp_price[$key];
                                $reph->amount=$request->subtotal_price[$key];
                                if($reph->save()){
                                    $stock=new Stock;
                                    $stock->return_id=$data->id;
                                    $stock->chalan_no= $data->invoice_number;
                                    $stock->stock_date= now();
                                    $stock->distributor_id=$data->distributor_id;
                                    $stock->group_id=$request->group_id[$key];
                                    $stock->product_id=$request->product_id[$key];
                                    $stock->totalquantity_pcs=$request->total_pcs_return[$key];
                                    $stock->dp_pcs=$request->dp_price[$key];
                                    $stock->subtotal_dp_pcs=$request->subtotal_price[$key];
                                    $stock->status=1;
                                    $stock->status_history=1;
                                    //'0=out_sales,1=in_return,2=in_damage,3=in_purchase,4=out_damage,5=out_purchase_return'
                                    $stock->created_by= currentUserId();
                                    $stock->save();
                                }
                            }
                        }
                    }
                    DB::commit();
                }
            Toastr::success('Receive Successfully!');
            return redirect()->route(currentUser().'.returnproduct.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            DB::rollback();
            return back()->withInput();

        }
    }
    public function getReturnProduct(Request $request)
    {
        $product = Product::join('units', 'units.unit_style_id', '=', 'products.unit_style_id')->select('products.id','products.product_name','dp_price','products.group_id','units.qty as unit_qty')->where('distributor_id', $request->supplier_id)->get();
        $pid = Product::where('distributor_id', $request->supplier_id)->pluck('group_id');
        $group= Group::select('id','name')->whereIn('id',$pid)->get();

        $res=['group'=>$group,'data'=>$product];

        return response()->json($res, 200);
    }

    public function UnitDataGet(Request $request)
    {
        $productId=$request->product_id;
        $unitStyleId=Product::where('id', $productId)->where('status',0)->pluck('unit_style_id');
        $unit=Unit::whereIn('unit_style_id', $unitStyleId)->pluck('qty');
        return response()->json($unit,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\ReturnProduct  $returnProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReturnProduct $returnProduct)
    {
        //
    }
}
