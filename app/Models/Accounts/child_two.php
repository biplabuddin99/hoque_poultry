<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class child_two extends Model
{
    use HasFactory,SoftDeletes;
    public function child_one(){
        return $this->belongsTo(child_one::class);
    }
}
