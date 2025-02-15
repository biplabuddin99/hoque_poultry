<?php

namespace App\Models\Settings;

use App\Models\Settings\Location\Area;
use App\Models\User;
use App\Models\Settings\ShopBalance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    public function dsr(){
        return $this->belongsTo(User::class,'dsr_id','id');
    }
    public function shopBalances(){
        return $this->hasMany(ShopBalance::class,'shop_id','id');
    }

    public function sr(){
        return $this->belongsTo(User::class,'sr_id','id');
    }
    public function area(){
        return $this->belongsTo(Area::class,'area_name','id');
    }
    public function distributor(){
        return $this->belongsTo(Supplier::class,'sup_id','id');
    }
}
