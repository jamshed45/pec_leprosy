<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeathFacility extends Model
{
    use HasFactory;
    protected $table = 'health_facilities';
    protected $primaryKey  = 'health_facility_id';
    protected $fillable = [
        'province_id', 'tehsil_id', 'health_facility_name' 
    ];
}
