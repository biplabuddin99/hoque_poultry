<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class SalesDetails extends Model
{
    use HasFactory;
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function sales(){
        return $this->belongsTo(Sales::class,'sales_id','id');
    }
}
