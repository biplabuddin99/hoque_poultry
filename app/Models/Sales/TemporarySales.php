<?php

namespace App\Models\Sales;

use App\Models\User;
use App\Models\Settings\Shop;
use App\Models\Product\Product;
use App\Models\Settings\Supplier;
use App\Models\Settings\Location\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemporarySales extends Model
{
    use HasFactory;
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id');
       }
       public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function dsr(){
        return $this->belongsTo(User::class,'dsr_id','id');
       }
    public function sr(){
        return $this->belongsTo(User::class,'sr_id','id');
       }
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
       }

    public function distributor(){
        return $this->belongsTo(Supplier::class,'distributor_id','id');
    }

       public function temporary_sales_details(){
        return $this->hasMany(TemporarySalesDetails::class,'tem_sales_id','id');
    }

}
