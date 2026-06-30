<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourDairy extends Model
{
    use HasFactory;
    protected $table = 'tour_dairy_forms';
    protected $primaryKey  = 'td_form_id';
    protected $fillable = [
        'user_id', 'td_detail', 'entry_month', 'verify_status', 'send_status', 'approve_status' , 'signature'
    ];
}
