<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempShopBalance extends Model
{
    use HasFactory;
    public function due_shop(){
        return $this->belongsTo(Shop::class,'old_due_shop_id','id');
       }
}
