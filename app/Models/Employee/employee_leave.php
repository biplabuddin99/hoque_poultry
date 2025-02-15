<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class employee_leave extends Model
{
    use HasFactory,SoftDeletes;
    public function employe(){
        return $this->belongsTo(employee::class,'employee_id','id');
    }
}
