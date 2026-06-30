<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormCategories extends Model
{
    use HasFactory;
    protected $table = 'pec_form_categories';
    protected $primaryKey  = 'pec_form_cat_id';
    protected $fillable = [
        'refer_by_name', 'slug'
    ];


    public function referralForms()
    {
        return $this->hasMany(RefferalForm::class, 'refer_by', 'pec_form_cat_id');
    }
}
