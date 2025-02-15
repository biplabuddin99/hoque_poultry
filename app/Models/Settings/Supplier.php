<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Supplier_balance;

class Supplier extends Model
{
    use HasFactory,SoftDeletes;

    
    public function balances(){
        return $this->hasMany(Supplier_balance::class,'supplier_id','id');
    }
}
