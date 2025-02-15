<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Shop;
use App\Models\User;
use App\Models\Sales\SalesPayment;
use App\Models\Settings\Location\Area;
use App\Models\Settings\ShopBalance;
use App\Models\Settings\Supplier;

class Sales extends Model
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
    public function distributor(){
        return $this->belongsTo(Supplier::class,'distributor_id','id');
    }
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }
    public function sales_payment(){
        return $this->hasMany(SalesPayment::class,'sales_id','id');
    }
    public function shop_balance(){
        return $this->hasMany(ShopBalance::class,'sales_id','id');
    }

    public function sales_details(){
    return $this->hasMany(SalesDetails::class,'sales_id','id');
    }
}
