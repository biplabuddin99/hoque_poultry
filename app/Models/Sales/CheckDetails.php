<?php

namespace App\Models\Sales;

use App\Models\Settings\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckDetails extends Model
{
    use HasFactory;
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id');
    }
    public function sr(){
        return $this->belongsTo(User::class,'sr_id','id');
    }
}
