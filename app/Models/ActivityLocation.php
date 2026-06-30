<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivityLocation extends Model
{
    use HasFactory;
    protected $table = 'activity_locations';
    protected $primaryKey  = 'activity_location_id';
    protected $fillable = [
        // 'tehsil_id', 'center_name' 
    ];

    static function get_activity_location_name_by_id($activityLocationID)
    {
        $activity_info = DB::table('activity_locations')->where('activity_location_id',  $activityLocationID)->first();
        $name =  $activity_info->activity_name;
        return $name;
    }

}
