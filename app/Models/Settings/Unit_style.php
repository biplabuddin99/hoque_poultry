<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit_style extends Model
{
    use HasFactory;
    public function unit(){
        return $this->hasOne(Unit::class,'unit_style_id','id');
    }
}
