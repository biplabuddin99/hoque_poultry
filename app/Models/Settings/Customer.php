<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Customer_balance;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    public function balances(){
        return $this->hasMany(Customer_balance::class,'customer_id','id');
    }
}
