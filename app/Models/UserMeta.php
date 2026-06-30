<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;
    protected $table = 'usermeta';
    protected $primaryKey  = 'umeta_id';
    protected $fillable = [
        'user_id', 'parent_id', 'meta_key', 'meta_value'
    ];
}
