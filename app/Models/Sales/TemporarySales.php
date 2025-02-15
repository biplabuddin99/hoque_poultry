<?php

namespace App\Models\Sales;

use App\Models\Settings\Location\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Shop;
use App\Models\Settings\Supplier;
use App\Models\User;

class TemporarySales extends Model
{
    use HasFactory;
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id');
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
