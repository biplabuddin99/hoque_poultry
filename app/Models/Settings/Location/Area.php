<?php

namespace App\Models\Settings\Location;

use App\Models\Settings\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public function distributor(){
        return $this->belongsTo(Supplier::class,'distributor_id','id');
    }
}