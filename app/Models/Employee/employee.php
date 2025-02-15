<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class employee extends Model
{
    use HasFactory,SoftDeletes;
    public function designa(){
        return $this->belongsTo(designation::class,'designation_id','id');
    }
}
