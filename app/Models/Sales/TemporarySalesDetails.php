<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class TemporarySalesDetails extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function temporaray_sales(){
        return $this->belongsTo(TemporarySales::class,'tem_sales_id','id');
    }

}
