<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientEntry extends Model
{
    use HasFactory;
    protected $table = 'pec_center_patients_record';
    protected $primaryKey = 'center_patient_record_id';
    protected $fillable = [
        'user_id', 'activity_location_id', 'province_id', 'division_id', 'district_id', 'tehsil_id', 'center_id', 'records_set_unique_id', 'patient_type', 
        'ma', 'fa', 'mc', 'fc',  'clinics_ma', 'clinics_fa', 'clinics_mc', 'clinics_fc', 
        'home_field_ma', 'home_field_fa', 'home_field_mc', 'home_field_fc',
        'school_ma', 'school_fa', 'school_mc', 'school_fc', 
        'entry_at'
    ];
}
