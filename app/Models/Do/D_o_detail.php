<?php

namespace App\Models\Do;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Settings\Unit_style;

class D_o_detail extends Model
{
    use HasFactory;
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function unitstyle(){
        return $this->belongsTo(Unit_style::class,'unite_style_id','id');
    }
    public function doReference(){
        return $this->belongsTo(D_o::class,'do_id','id');
    }
}
