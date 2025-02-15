<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    public function ustyle(){
        return $this->belongsTo(Unit_style::class,'unit_style_id','id');
    }
}
