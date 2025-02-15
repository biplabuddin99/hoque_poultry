<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\Shop;
use App\Models\Settings\ShopBalance;
use App\Models\Product\Group;
use App\Models\Settings\Supplier;
use App\Models\Product\Product;
use App\Models\Do\D_o;
use App\Models\Do\D_o_detail;
use App\Models\Do\DoReceiveHistory;
use App\Models\Sales\Sales;
use App\Models\Sales\SalesDetails;
use App\Models\Sales\TemporarySales;
use App\Models\Sales\TemporarySalesDetails;
use App\Models\Settings\ShopCollection;
use App\Models\Stock\Stock;
use App\Models\User;
use App\Models\Settings\Location\Area;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function saleSummaryReport(Request $request)
    {
        $sales = TemporarySalesDetails::select(
            'temporary_sales_details.tem_sales_id',
            'temporary_sales_details.product_id',
            'temporary_sales_details.pcs_price',
            'temporary_sales_details.totalquantity_pcs',
            'temporary_sales_details.group_id',
            'temporary_sales.shop_id',
            'temporary_sales.dsr_id',
            'users.name',
            'shops.shop_name',
            DB::raw('SUM(temporary_sales_details.totalquantity_pcs) as total_sales_pcs'),
            DB::raw('SUM(temporary_sales_details.subtotal_price) as subtotal_price'),
        )
        ->join('temporary_sales', 'temporary_sales.id', '=', 'temporary_sales_details.tem_sales_id')
        ->leftjoin('users', 'users.id', '=', 'temporary_sales.dsr_id')
        ->leftjoin('shops', 'shops.id', '=', 'temporary_sales.shop_id');

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(temporary_sales.sales_date)'), [$request->fdate, $tdate]);
        }

        if ($request->product_id) {
            $sales->where('temporary_sales_details.product_id', $request->product_id);
        }
        if ($request->shop_id){
            $shopIds = $request->shop_id;
            $sales->whereIn('temporary_sales.shop_id', $shopIds);
        }

        if ($request->sr_id) {
            $sales->where('temporary_sales.sr_id', $request->sr_id);
        }
        if ($request->distributor_id) {
            $sales->where('temporary_sales.distributor_id', $request->distributor_id);
        }

        $sales->groupBy('temporary_sales_details.product_id');
        $sales = $sales->paginate(25);
        $userSr=User::where(company())->where('role_id',5)->get();
        $product = Product::select('id', 'product_name')->get();
        $shop = Shop::select('id', 'shop_name','area_name')->get();
        $distributor = Supplier::select('id', 'name')->get();

        return view('reports.saleSummary', compact('sales','userSr','distributor', 'product','shop'));
    }
    public function salesReport(Request $request)
    {
        $sales = SalesDetails::select(
            'sales_details.id as sales_details_id',
            'sales_details.sales_id',
            'sales_details.product_id',
            'sales_details.group_id',
            DB::raw('SUM(sales_details.total_damage_pcs) as total_damage_pcs'),
            DB::raw('SUM(sales_details.total_sales_pcs) as total_sales_pcs'),
            DB::raw('SUM(sales_details.subtotal_price) as subtotal_price'),
        )
        ->join('sales', 'sales.id', '=', 'sales_details.sales_id');

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }

        if ($request->product_id) {
            $sales->where('sales_details.product_id', $request->product_id);
        }

        if ($request->sr_id) {
            $sales->where('sales.sr_id', $request->sr_id);
        }
        if ($request->distributor_id) {
            $sales->where('sales.distributor_id', $request->distributor_id);
        }

        $sales->groupBy('sales_details.product_id');
        $sales = $sales->paginate(50);
        $userSr=User::where(company())->where('role_id',5)->get();
        $product = Product::select('id', 'product_name')->get();
        $distributor = Supplier::select('id', 'name')->get();

        return view('reports.salesReport', compact('sales','userSr','distributor', 'product'));
    }


    public function stockreport(Request $request)
    {
        $groups = Group::where(company())->select('id','name')->get();
        $distributors = Supplier::where(company())->select('id','name')->get();
        $products = Product::where(company())->select('id','product_name')->get();

        $stockQuery = DB::table('stocks')
        ->join('products', 'products.id', '=', 'stocks.product_id')
        ->join('groups', 'groups.id', '=', 'products.group_id')
        ->join('suppliers', 'suppliers.id', '=', 'products.distributor_id')
        ->select(
            'products.product_name','products.dp_price as product_dp','groups.name as group_name','suppliers.name as supplier_name',
            'stocks.*',
            DB::raw('SUM(CASE WHEN stocks.status = 0 THEN stocks.totalquantity_pcs ELSE 0 END) as outs'),
            DB::raw('SUM(CASE WHEN stocks.status = 1 THEN stocks.totalquantity_pcs ELSE 0 END) as ins')
        );

        // if ($request->fdate) {
        //     $tdate = $request->tdate ?: $request->fdate;
        //     $stockQuery->whereBetween(DB::raw('date(stocks.stock_date)'), [$request->fdate, $tdate]);
        // }
        
        if ($request->fdate) {
            $stockQuery->where(DB::raw('date(stocks.stock_date)'),'<', $request->fdate);
        }
        if ($request->group_id)
            $stockQuery->where('products.group_id',$request->group_id);
        if ($request->distributor_id){
            $stock =$stockQuery->where('products.distributor_id',$request->distributor_id)->groupBy('products.group_id','products.distributor_id','products.product_name')->get();
        }else{
            $stock = $stockQuery
                ->groupBy('products.group_id','products.distributor_id','products.product_name')->paginate(20);
        }
           // return $stock;
        return view('reports.stockReport', compact('stock','groups','products','distributors'));

        // $stock= DB::select("SELECT products.product_name,stocks.*,sum(stocks.totalquantity_pcs) as qty FROM `stocks` join products on products.id=stocks.product_id $where GROUP BY stocks.product_id");
        // return view('reports.stockReport',compact('stock'));
    }

    public function stockindividual($id)
    {
        // $company = company()['company_id'];
        // $where = '';
        // $salesItem = SalesDetails::where('product_id', $id)->where('company_id', $company)->get();
        $stock = Stock::where('product_id',$id)->orderBy('stock_date','ASC')->get();
        $product = Product::where('id',$id)->first();

        return view('reports.stockReportIndividual', compact('stock','product'));
    }

    public function cashCollection(Request $request)
    {
        $sales = Sales::where('today_final_cash','>',0)->orderBy('id','DESC');
        $userDsr = User::where('role_id',4)->get();
        $shop = Shop::all();
        $distributor = Supplier::all();
        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->dsr_id)
            $sales->where('dsr_id',$request->dsr_id);
        if ($request->shop_id)
            $sales->where('shop_id',$request->shop_id);
        if ($request->distributor_id)
            $sales->where('distributor_id',$request->distributor_id);

        $sales = $sales->paginate(50);
        //return $sales;
        $userSr=User::where(company())->where('role_id',5)->get();
        return view('reports.cashCollection',compact('sales','userDsr','shop','distributor'));
    }

    public function damageProductList(Request $request)
    {
        $groups = Group::where(company())->select('id','name')->get();
        $distributors = Supplier::where(company())->select('id','name')->get();
        $sr = User::where(company())->where('role_id',5)->select('id','name')->get();
        $products = Product::where(company())->select('id','product_name')->get();

        $stockQuery = DB::table('stocks')
        ->join('products', 'products.id', '=', 'stocks.product_id')
        ->join('groups', 'groups.id', '=', 'products.group_id')
        ->join('sales', 'sales.id', '=', 'stocks.sales_id')
        ->join('suppliers', 'suppliers.id', '=', 'products.distributor_id')
        ->where('stocks.status_history', '=', 2)
        ->select(
            'products.product_name','products.dp_price as product_dp','groups.name as group_name','sales.sr_id as sr','suppliers.name as supplier_name',
            'stocks.*',DB::raw('SUM(stocks.totalquantity_pcs) as totalquantity_pcs'));

        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $stockQuery->whereBetween(DB::raw('date(stocks.stock_date)'), [$request->fdate, $tdate]);
        }
        if ($request->group_id)
            $stockQuery->where('products.group_id',$request->group_id);
        if ($request->distributor_id)
            $stockQuery->where('products.distributor_id',$request->distributor_id);
        if ($request->sr_id)
            $stockQuery->where('sales.sr_id',$request->sr_id);

        $stock = $stockQuery
            ->groupBy('stocks.product_id')
            ->get();
        return view('reports.damageProductList', compact('stock','groups','products','distributors','sr'));
    }
    public function demageindividual($id)
    {
        // $company = company()['company_id'];
        // $where = '';
        // $salesItem = SalesDetails::where('product_id', $id)->where('company_id', $company)->get();
        $stock = Stock::where('product_id',$id)->where('status_history',2)->get();
        $product = Product::where('id',$id)->first();

        return view('reports.demageReportIndividual', compact('stock','product'));
    }

    public function SRreport(Request $request)
    {
        $sales = Sales::orderBy('id','DESC')->where(company());
        //$sales = Sales::orderBy('id','DESC')->where(company())->where('sales.sr_id',$request->sr_id);
        if ($request->fdate) {
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->sr_id)
            $sales->where('sales.sr_id',$request->sr_id);

        $sales = $sales->paginate(20);
        $userSr=User::where(company())->where('role_id',5)->get();
        return view('reports.srReport',compact('sales','userSr'));
    }

    public function srreportProduct(Request $request)
    {
        $products = Product::where(company())->select('id','product_name')->get();
        $userSr=User::where(company())->where('role_id',5)->get();
        $sales = Sales::with('sales_details')->where(company())->where('sales.sr_id',$request->sr_id);
        if ($request->fdate){
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if($request->product_id){
            $productId=$request->product_id;
            $sales=$sales->whereHas('sales_details',function($q) use ($productId){
                $q->where('product_id', $productId);
            });
        }

        $sales=$sales->orderBy('id', 'DESC')->get();
        // return $sales;
        return view('reports.srReportProduct',compact('sales','products','userSr'));
    }

    public function dsrsalary(Request $request)
    {
        $userDsr=User::where(company())->where('role_id',4)->get();
        $sales = Sales::where(company());
        if ($request->fdate){
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->dsr_id)
            $sales->where('sales.dsr_id',$request->dsr_id);

        $sales=$sales->orderBy('id', 'DESC')->get();
        return view('reports.dsrsalary',compact('sales','userDsr'));
    }
    public function expense(Request $request)
    {
        $distributor = Supplier::where(company())->get();
        $userSr=User::where(company())->where('role_id',5)->get();
        $userDsr=User::where(company())->where('role_id',4)->get();
        $sales = Sales::where(company());

        if ($request->fdate){
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->distributor_id)
            $sales->where('sales.distributor_id',$request->distributor_id);
        if ($request->sr_id)
            $sales->where('sales.sr_id',$request->sr_id);
        if ($request->dsr_id)
            $sales->where('sales.dsr_id',$request->dsr_id);

        $sales=$sales->orderBy('id', 'DESC')->get();
        return view('reports.expense',compact('sales','distributor','userSr','userDsr'));
    }
    public function salesCommission(Request $request)
    {
        $distributor = Supplier::where(company())->get();
        $userSr=User::where(company())->where('role_id',5)->get();
        $userDsr=User::where(company())->where('role_id',4)->get();
        $sales = Sales::where(company());
        
        if ($request->fdate){
            $tdate = $request->tdate ?: $request->fdate;
            $sales->whereBetween(DB::raw('date(sales.sales_date)'), [$request->fdate, $tdate]);
        }
        if ($request->distributor_id)
            $sales->where('sales.distributor_id',$request->distributor_id);
        if ($request->sr_id)
            $sales->where('sales.sr_id',$request->sr_id);
        if ($request->dsr_id)
            $sales->where('sales.dsr_id',$request->dsr_id);

        $sales=$sales->orderBy('id', 'DESC')->get();
        return view('reports.commission',compact('sales','distributor','userSr','userDsr'));
    }

    public function ShopDue(Request $request)
    {
        $shop = Shop::where(company())->get();
        $distributor = Supplier::where(company())->get();
        $area= Area::select('id','name')->get();
        $query = ShopBalance::join('shops', 'shops.id', '=', 'shop_balances.shop_id')
        ->leftJoin('suppliers','shops.sup_id','=','suppliers.id')
        ->groupBy('shop_balances.shop_id')
        ->select('shops.*', 'shop_balances.*','suppliers.name as distributor_name',
            DB::raw('SUM(CASE WHEN shop_balances.status = 0 THEN shop_balances.balance_amount ELSE 0 END) as balance_out'),
            DB::raw('SUM(CASE WHEN shop_balances.status = 0 THEN shop_balances.check_collect_amount ELSE 0 END) as collect_in'),
            DB::raw('SUM(CASE WHEN shop_balances.status = 0 THEN shop_balances.collect_amount ELSE 0 END) as collect_in_cash'),
            DB::raw('SUM(CASE WHEN shop_balances.status = 1 THEN shop_balances.balance_amount ELSE 0 END) as balance_in')
        );
        if ($request->area) {
            $query=$query->whereHas('shop.area', function($query) use ($request) {
                $query->where('id', $request->area);
            });
        }
        if ($request->shop_name) {
            $query->where('shop_balances.shop_id', $request->shop_name);
            }
        if ($request->distributor_id) {
            $query->where('shops.sup_id', $request->distributor_id);
            }
        $data = $query->get();
        return view('reports.shopdue', compact('shop','distributor','data','area'));
    }
    public function ShopBalanceHistory($id){
        $shop = Shop::where('id',$id)->first();
        $data = ShopBalance::where('shop_id',$id)->get();
        $dueCollection= ShopCollection::where('status',1)->where('shop_id',$id)->get();
        return view('reports.shopBalanceHistory',compact('shop','data','dueCollection'));
    }
    public function ShopBalanceHistoryTwo($id){
        $shop = Shop::where('id',$id)->first();
        /*$shopBalances = DB::table('shop_balances')
            ->select('id', 'status', DB::raw("CASE 
                WHEN status = '0' AND new_due_date != '' THEN new_due_date 
                WHEN status = '0' THEN check_date 
                ELSE old_due_date 
            END AS date"), 'balance_amount', 'check_collect_amount', 'check_type')
            ->where('shop_id', $id);

        $dueCollections = DB::table('shop_collections')
            ->select('id', 'status', DB::raw('collection_date AS date'), DB::raw('NULL AS balance_amount'), DB::raw('collect_amount AS check_collect_amount'), DB::raw('NULL AS check_type'))
            ->where('status', 1)
            ->where('shop_id', $id);

        $data = $shopBalances->unionAll($dueCollections)
            ->orderBy('date')
            ->get();
        dd($data);*/
        $data = ShopBalance::where('shop_id',$id)->get();
        $dueCollection= ShopCollection::where('status',1)->where('shop_id',$id)->get();
        return view('reports.shopBalanceHistoryTwo',compact('shop','data'));
    }

    public function undeliverdProduct(Request $request)
    {
        if ($request->supplier_id) {
            $do_reference = D_o::where('supplier_id',$request->supplier_id)->pluck('id');
            $dodetails= D_o_detail::whereIn('do_id',$do_reference)->get();
            // $histotry = DoReceiveHistory::whereIn('do_id',$do_reference)->groupBy('product_id')->get();
             //return $dodetails;
            // $commonProductIds = array_intersect($dodetails->pluck('product_id')->toArray(), $history->pluck('product_id')->toArray());

            // $do_reference = DoReceiveHistory::where(function($query) use ($histotry,$dodetails){
            //     if($dodetails){
            //         $query->orWhere(function($query) use ($dodetails){
            //             $query->whereIn('do_id',$dodetails);
            //         });
            //     }
            //     if($histotry){
            //         $query->orWhere(function($query) use ($histotry){
            //             $query->whereIn('do_id',$histotry);
            //         });
            //     }
            // })->get();
        }else {
            $dodetails = collect();
        }
        return view('do.undeliverd-list', compact('dodetails'));
    }
}
