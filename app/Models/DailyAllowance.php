<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAllowance extends Model
{
    use HasFactory;
    protected $table = 'da_forms';
    protected $primaryKey  = 'da_form_id';
    protected $fillable = [
        'user_id', 'da_detail', 'entry_month', 'verify_status', 'send_status', 'approve_status' , 'signature'
    ];
}
