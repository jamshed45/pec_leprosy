<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table = 'doctors';
    protected $primaryKey  = 'doctor_id';
    protected $fillable = [
        'health_facility_id', 'province_id', 'tehsil_id', 'doctor_name' 
    ];
}
