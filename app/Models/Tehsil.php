<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tehsil extends Model
{
    use HasFactory;
    protected $table = 'tehsil';
    protected $primaryKey  = 'tehsil_id';
    protected $fillable = [
        'province_id', 'division_id', 'tehsil_id', 'tehsil_name' 
    ];
}
