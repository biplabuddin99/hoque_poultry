<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Shop;

class SalesPayment extends Model
{
    use HasFactory;
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id');
    }
    public function sale(){
        return $this->belongsTo(Sales::class,'sales_id','id');
    }
}
