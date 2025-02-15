<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;

use App\Models\Product\Product;
use Illuminate\Http\Request;
use App\Models\Product\Group;
use App\Models\Settings\Supplier;
use App\Models\Product\Category;
use App\Models\Settings\Unit_style;
use App\Models\Settings\Unit;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Traits\ImageHandleTraits;
use App\Models\User;
use Exception;

class ProductController extends Controller
{
    use ImageHandleTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Group::where(company())->select('id','name')->get();
        $distributors = Supplier::where(company())->select('id','name')->get();
        $product=Product::orderBy('id','DESC');

        if ($request->group_id)
        $product->where('products.group_id',$request->group_id);
        if ($request->distributor_id)
        $product->where('products.distributor_id',$request->distributor_id);

        $product = $product->paginate(25);
        return view('product.product.index',compact('product','groups','distributors'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group=Group::all();
        $category=Category::all();
        $unit_style=Unit_style::all();
        $base_unit=Unit::all();
        $user=User::where('id',currentUserId())->where('role_id',3)->select('distributor_id')->first();
        return view('product.product.create',compact('group','category','unit_style','base_unit','user'));
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
            $data=new Product;
            $data->group_id = $request->group_id;
            $data->category_id = $request->category_id;
            $data->distributor_id = $request->distributor_id;
            $data->product_name = $request->product_name;
            $data->dp_price = $request->dp_price;
            $data->tp_price = $request->tp_price;
            $data->tp_free = $request->tp_free;
            $data->mrp_price = $request->mrp_price;
            $data->free = $request->free;
            $data->free_ratio = $request->free_ratio;
            $data->free_taka = $request->free_taka;
            $data->adjust = $request->adjust;
            $data->unit_style_id = $request->unit_style_id;
            $data->base_unit = $request->base_unit;
            $data->color = $request->color;
            $data->size = $request->size;
            $data->weight = $request->weight;
            $data->status = 0;
            if($request->has('image'))
            $data->image=$this->resizeImage($request->image,'uploads/product_img/'.company()['company_id'],true,200,200,false);

            $data->company_id=company()['company_id'];
            $data->created_by= currentUserId();
            if($data->save()){
            Toastr::success('Create Successfully!');
            return redirect()->route(currentUser().'.product.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            return back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail(encryptor('decrypt',$id));
        $group=Group::all();
        $category=Category::all();
        $unit_style=Unit_style::all();
        $base_unit=Unit::all();
        return view('product.product.edit',compact('product','group','category','unit_style','base_unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try{
            $data= Product::findOrFail(encryptor('decrypt',$id));
            $data->group_id = $request->group_id;
            $data->distributor_id = $request->distributor_id;
            $data->category_id = $request->category_id;
            $data->product_name = $request->product_name;
            $data->dp_price = $request->dp_price;
            $data->tp_price = $request->tp_price;
            $data->tp_free = $request->tp_free;
            $data->mrp_price = $request->mrp_price;
            $data->free = $request->free;
            $data->free_ratio = $request->free_ratio;
            $data->free_taka = $request->free_taka;
            $data->adjust = $request->adjust;
            $data->unit_style_id = $request->unit_style_id;
            $data->base_unit = $request->base_unit;
            $data->color = $request->color;
            $data->size = $request->size;
            $data->weight = $request->weight;
            $data->status = 0;

            if($request->has('image')){
                if($data->image){
                    if($this->deleteImage($data->image,'uploads/product_img/'.company()['company_id'])){
                        $data->image=$this->resizeImage($request->image,'uploads/product_img/'.company()['company_id'],true,200,200,false);
                    }
                }else{
                    $data->image=$this->resizeImage($request->image,'uploads/product_img/'.company()['company_id'],true,200,200,false);
                }
            }
            $data->company_id=company()['company_id'];
            $data->updated_by= currentUserId();

            if($data->save()){
            Toastr::success('Updated Successfully!');
            return redirect()->route(currentUser().'.product.index');
            } else{
            Toastr::warning('Please try Again!');
             return redirect()->back();
            }

        }
        catch (Exception $e){
            dd($e);
            return back()->withInput();

        }
    }

    public function updateProductPrice(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'tp_price' => 'required|numeric',
        ]);

        $product = Product::find($request->input('product_id'));

        if ($product) {
            $product->tp_price = $request->input('tp_price');
            $product->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
    }

    public function product_price(Request $request)
    {
        $groups = Group::where(company())->select('id','name')->get();
        $distributors = Supplier::where(company())->select('id','name')->get();
        $product=Product::orderBy('id','DESC');
        $unit_style=Unit_style::all();

        if ($request->distributor_id)
            $product=Product::where('products.distributor_id',$request->distributor_id)->get();
        return view('product.product.product_price',compact('product','unit_style','groups','distributors'));
    }

    public function updateProductPricesByDistribute(Request $request)
    {
        foreach ($request->input('unit_style_id') as $productId => $unitStyleId) {
            $product = Product::find($productId);
            if ($product) {
                // Fetch values for this specific product
                $product->unit_style_id = $unitStyleId;
                $product->free_ratio = $request->input("free_ratio.$productId");
                $product->free = $request->input("free_pcs.$productId");
                $product->dp_price = $request->input("dp_price.$productId");
                $product->tp_price = $request->input("tp_price.$productId");
                $product->tp_free = $request->input("tp_free.$productId");
                $product->mrp_price = $request->input("mrp_price.$productId");
                $product->free_taka = $request->input("free_taka.$productId");
                $product->adjust = $request->input("adjust.$productId");

                // Save the updated product
                $product->save();
            }
        }

        return redirect()->route(currentUser().'.product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data= Product::findOrFail(encryptor('decrypt',$id));
        $data->delete();
        Toastr::error('Opps!! You Delete Permanently!!');
        return redirect()->back();
    }

    public function UnitPcsGet(Request $request)
    {
        $unitStyle=$request->unit_style_id;
        $unit=Unit::where('unit_style_id', $unitStyle)->pluck('qty');
        // $unit=Unit::where('unit_style_id', $unitStyle)->where('name','pcs')->pluck('qty');
        // return $unit;
        return response()->json($unit,200);
    }
}
