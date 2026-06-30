<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Permissions\HasPermissionsTrait;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'password_visible',
        'img',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static function add_user_meta($userID, $metaKey, $MetaValue)
        {
            DB::insert('insert into usermeta (user_id, meta_key, meta_value) values (?, ?, ?)', [$userID, $metaKey, $MetaValue]);
        }

    static function get_user_meta($userID, $metaKey)
    {

        $usermeta = DB::table('usermeta')
            ->select('meta_value')
            ->where('user_id', $userID)
            ->where('meta_key', $metaKey)
            ->get()
            ->toArray();

         return $usermeta;
    }

    static function current_user_role()
    {
        $user = Auth::user();

        $user_role = DB::table('users_role')
            ->where('id', $user->user_role_id)
            ->get()
            ->toArray();

        return $user_role;
    }

    static function current_user_id()
    {
        $user = Auth::user();
        return $user->id;
    }

    static function get_username_by_id($userID)
    {
        $user_info = DB::table('users')->where('id',  $userID)->first();
        $user_name =  $user_info->name;
        return $user_name;
    }


    public function referralForms()
{
    return $this->hasMany(RefferalForm::class, 'user_id', 'id');
}


}
