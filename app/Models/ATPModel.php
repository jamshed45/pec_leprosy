<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ATPModel extends Model
{
    use HasFactory;
    protected $table = 'atp_forms';
    protected $primaryKey  = 'atp_form_id';
    protected $fillable = [
         'month_id', 'activity_location_id', 'user_id', 'center_id', 'tehsil_id', 'province_id', 'district_id', 'division_id', 'entry_date', 'entry_month', 'verify_id', 'verify_status', 'verify_status_date', 'submit_status', 'submit_status_date', 'approve_id','approve_status','approve_status_date','created_at','updated_at','atp_detail',
    ];
}
