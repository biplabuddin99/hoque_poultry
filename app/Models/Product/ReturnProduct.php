<?php

namespace App\Models\Product;

use App\Models\Settings\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    use HasFactory;
    public function distributor(){
        return $this->belongsTo(Supplier::class,'distributor_id','id');
    }
}
