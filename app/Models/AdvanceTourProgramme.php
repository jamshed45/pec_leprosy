<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceTourProgramme extends Model
{
    use HasFactory;
    protected $table = 'atp_forms';
    protected $primaryKey  = 'atp_form_id';
}
