<?php

namespace App\Models\Do;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Supplier;
use App\Models\Do\D_o_detail;
use App\Models\Settings\Company;

class D_o extends Model
{
    use HasFactory,SoftDeletes;

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function details(){
        return $this->hasMany(D_o_detail::class,'do_id','id');
    }
}
