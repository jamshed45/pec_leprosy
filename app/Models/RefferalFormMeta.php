<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefferalFormMeta extends Model
{
    use HasFactory;

    protected $table = 'pec_refferal_form_meta';
    protected $primaryKey = 'pec_refferal_form_meta_id';
    protected $fillable = [
        'refferal_forms_id', 'user_id', 'center_id', 'province_id', 'tehsil_id', 'doctor_id', 'health_facility_id', 'patient_type', 'diabetic', 'diabetic_value', 'entry_at', 'remark'
    ];


        public function referralForm()
    {
        return $this->belongsTo(RefferalForm::class, 'refferal_forms_id', 'refferal_forms_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function healthFacility()
    {
        return $this->belongsTo(HeathFacility::class, 'health_facility_id', 'health_facility_id');
    }

}
