<?php

namespace App\Models\workshop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workshopBoss extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_dr',
        'name_en',
        'last_name_dr',
        'last_name_en',
        'f_name_da',
        'phone',
        'passport_no',
        'country',
        'main_province',
        'main_district',
        'main_village',
        'current_province',
        'current_district',
        'current_village',
        'type_residence_info',
        'company_id'
    ];
    public $timestamps = true;
}
