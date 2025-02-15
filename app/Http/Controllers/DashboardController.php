<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock\Stock;
use App\Models\Settings\ShopBalance;
use App\Models\Settings\Supplier;
use App\Models\Do\D_o;
use App\Models\Do\D_o_detail;
use DB;

class DashboardController extends Controller
{
    /*
    * admin dashboard
    */
    public function adminDashboard(){
        return view('dasbhoard.admin');
    }

    /*
    * owner dashboard
    */
    // YourModel::query()
    // ->select(DB::raw('COALESCE(SUM(column1 + IFNULL(column2, 0)), 0) as total_sum'))
    // ->first()
    // ->total_sum;
    public function ownerDashboard(){
        $stock_data = Stock::select(
            'stocks.product_id',
            DB::raw('SUM(CASE WHEN stocks.status = 0 THEN stocks.totalquantity_pcs ELSE 0 END) as outs'),
            DB::raw('SUM(CASE WHEN stocks.status = 1 THEN stocks.totalquantity_pcs ELSE 0 END) as ins'),
            DB::raw('AVG(CASE WHEN stocks.status = 0 THEN products.tp_price ELSE NULL END) as avg_price_tp_out'),
            DB::raw('AVG(CASE WHEN stocks.status = 1 THEN products.tp_price ELSE NULL END) as avg_price_tp_in'),
            DB::raw('AVG(CASE WHEN stocks.status = 0 THEN products.tp_free ELSE NULL END) as avg_price_tp_free_out'),
            DB::raw('AVG(CASE WHEN stocks.status = 1 THEN products.tp_free ELSE NULL END) as avg_price_tp_free_in'),
            DB::raw('AVG(CASE WHEN stocks.status = 0 THEN products.dp_price ELSE NULL END) as avg_price_dp_out'),
            DB::raw('AVG(CASE WHEN stocks.status = 1 THEN products.dp_price ELSE NULL END) as avg_price_dp_in')
        )
        ->join('products', 'products.id', '=', 'stocks.product_id')  // Joining with products table
        ->groupBy('stocks.product_id')  // Grouping by product_id
        ->havingRaw('ins - outs > 0')  // Filter products with positive stock
        ->get();

            
        $totalstock = 0;
        $totalstockPriceTp = 0;
        $totalstockPriceDp = 0;
        $totalstockPriceTpFree = 0;
        foreach ($stock_data as $stock) {
            $ins = $stock->ins ?? 0;
            $outs = $stock->outs ?? 0;
            $avg_price_tp_in = $stock->avg_price_tp_in ?? 0;
            $avg_price_tp_out = $stock->avg_price_tp_out ?? 0;
            $avg_price_dp_in = $stock->avg_price_dp_in ?? 0;
            $avg_price_dp_out = $stock->avg_price_dp_out ?? 0;
            $avg_price_tp_free_in = $stock->avg_price_tp_free_in ?? 0;
            $avg_price_tp_free_out = $stock->avg_price_tp_free_out ?? 0;
        
            // Calculate total stock for this product
            $totalstock += $ins - $outs;
        
            // Calculate total stock price for TP price
            $totalstockPriceTp += ($ins * $avg_price_tp_in) - ($outs * $avg_price_tp_out);

            // Calculate total stock price for DP price
            $totalstockPriceDp += ($ins * $avg_price_dp_in) - ($outs * $avg_price_dp_out);
        
            // Calculate total stock price for TP free
            $totalstockPriceTpFree += ($ins * $avg_price_tp_free_in) - ($outs * $avg_price_tp_free_out);
        }
        $checkbalance = ShopBalance::where('cash_type',1)->sum('balance_amount');
        $customerDue = ShopBalance::where('status',"0")->sum('balance_amount');
        $suppliers= Supplier::where(company())->orderBy('id','DESC')->get();

        // $total_undeliver = D_o_detail::sum(DB::raw('CASE WHEN qty_pcs > receive_qty THEN qty_pcs - receive_qty ELSE 0 END'))
        //         + D_o_detail::sum(DB::raw('CASE WHEN free > receive_free_qty THEN free - receive_free_qty ELSE 0 END')); 
        $unData = D_o_detail::join('products', 'products.id', '=', 'd_o_details.product_id')
            ->select(
                DB::raw('SUM(CASE WHEN d_o_details.qty_pcs > d_o_details.receive_qty THEN d_o_details.qty_pcs - d_o_details.receive_qty ELSE 0 END) as total_undelivered_qty'),
                DB::raw('SUM(CASE WHEN d_o_details.free > d_o_details.receive_free_qty THEN d_o_details.free - d_o_details.receive_free_qty ELSE 0 END) as total_undelivered_free_qty'),
                DB::raw('SUM(CASE WHEN d_o_details.qty_pcs > d_o_details.receive_qty THEN (d_o_details.qty_pcs - d_o_details.receive_qty) * products.dp_price ELSE 0 END) as total_dp_price'),
                DB::raw('SUM(CASE WHEN d_o_details.free > d_o_details.receive_free_qty THEN (d_o_details.free - d_o_details.receive_free_qty) * products.dp_price ELSE 0 END) as total_dp_price_free'),
                DB::raw('SUM(CASE WHEN d_o_details.qty_pcs > d_o_details.receive_qty THEN (d_o_details.qty_pcs - d_o_details.receive_qty) * products.tp_price ELSE 0 END) as total_tp_price'),
                DB::raw('SUM(CASE WHEN d_o_details.free > d_o_details.receive_free_qty THEN (d_o_details.free - d_o_details.receive_free_qty) * products.tp_price ELSE 0 END) as total_tp_price_free'),
                DB::raw('SUM(CASE WHEN d_o_details.qty_pcs > d_o_details.receive_qty THEN (d_o_details.qty_pcs - d_o_details.receive_qty) * products.tp_free ELSE 0 END) as total_tp_free_price'),
                DB::raw('SUM(CASE WHEN d_o_details.free > d_o_details.receive_free_qty THEN (d_o_details.free - d_o_details.receive_free_qty) * products.tp_free ELSE 0 END) as total_tp_free_receive_price')
            )
            ->first();

        $total_undelivered_qty = $unData->total_undelivered_qty ?? 0;
        $total_undelivered_free_qty = $unData->total_undelivered_free_qty ?? 0;
        $total_undeliver_qty = $total_undelivered_qty + $total_undelivered_free_qty;

        $unQtyDp = $unData->total_dp_price ?? 0;
        $unQtyDpRec = $unData->total_dp_price_free ?? 0;
        $totalUndQtyDpPrice = $unQtyDp + $unQtyDpRec;
        
        $unQtyTp = $unData->total_tp_price ?? 0;
        $unQtyTpRec = $unData->total_tp_price_free ?? 0;
        $totalUndQtyTpPrice = $unQtyTp + $unQtyTpRec;
        
        $unQtyTpFree = $unData->total_tp_free_price ?? 0;
        $unQtyTpFreeRec = $unData->total_tp_free_receive_price ?? 0;
        $totalUndQtyTpFreePrice = $unQtyTpFree + $unQtyTpFreeRec;

        return view('dasbhoard.owner',compact('checkbalance','customerDue','suppliers','totalstock','totalstockPriceTp','totalstockPriceDp','totalstockPriceTpFree','total_undeliver_qty','totalUndQtyDpPrice','totalUndQtyTpPrice','totalUndQtyTpFreePrice'));
    }

    /*
    * manager dashboard
    */
    public function managerDashboard(){
        return view('dasbhoard.manager');
    }

    /*
    * salesrepresentative dashboard
    */
    public function salesrepresentativeDashboard(){
        return view('dasbhoard.dsr');
    }

    /*
    * JSO dashboard
    */
    public function jsoDashboard(){
        return view('dasbhoard.jso');
    }

    /*
    * accountant dashboard
    */
    public function accountantDashboard(){
        return view('dasbhoard.accountant');
    }
}
