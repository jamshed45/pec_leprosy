<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Center extends Model
{
    use HasFactory;
    protected $table = 'pec_center';
    protected $primaryKey  = 'center_id';
    protected $fillable = [
        'user_id', 'activity_location_id', 'province_id', 'division_id', 'district_id', 'tehsil_id', 'center_name' 
    ];

    static function get_center_name_by_id($centerID)
    {
        $user_info = DB::table('pec_center')->where('center_id',  $centerID)->first();
        $name =  $user_info->center_name;
        return $name;
    }
}
