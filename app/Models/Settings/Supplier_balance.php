<?php

namespace App\Models\Settings;
use App\Models\Settings\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier_balance extends Model
{
    use HasFactory;
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

}
