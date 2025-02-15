<?php

namespace App\Models\Settings;

use App\Models\Sales\Sales;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCollection extends Model
{
    use HasFactory;
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id');
    }
    public function sales_date(){
        return $this->belongsTo(Sales::class,'sales_id','id');
    }
    public function collectionUser(){
        return $this->belongsTo(User::class,'collection_by','id');
    }
}
