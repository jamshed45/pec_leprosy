<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefferalForm extends Model
{
    use HasFactory;

    protected $table = 'pec_refferal_forms';
    protected $primaryKey = 'refferal_forms_id';
    protected $fillable = [
        'user_id', 'center_id', 'province_id', 'tehsil_id', 'full_name', 'relation', 'relative_name', 'gender', 'dob', 'age', 'cnic_number', 'contact_number', 'address', 'cat_book_number', 'refer_by',  'referrals', 'status' , 'entry_at'
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}


public function referByCategory()
{
    return $this->belongsTo(FormCategories::class, 'refer_by', 'pec_form_cat_id');
}

public function referralsCategory()
{
    return $this->belongsTo(FormCategories::class, 'referrals', 'pec_form_cat_id');
}

public function meta()
{
    return $this->hasMany(RefferalFormMeta::class, 'refferal_forms_id', 'refferal_forms_id');
}

public function operatedBy()
{
    return $this->hasOne(RefferalFormMeta::class, 'refferal_forms_id', 'refferal_forms_id')
                ->where('operated_by', 1);
}

public function referTo()
{
    return $this->hasOne(RefferalFormMeta::class, 'refferal_forms_id', 'refferal_forms_id')
                ->where('refer_to', 1);
}




}
