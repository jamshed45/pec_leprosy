<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravellingAllowance extends Model
{
    use HasFactory;
    protected $table = 'ta_forms';
    protected $primaryKey  = 'ta_form_id';
    protected $fillable = [
        'user_id', 'da_detail', 'entry_month', 'verify_status', 'send_status', 'approve_status' 
    ];
}
