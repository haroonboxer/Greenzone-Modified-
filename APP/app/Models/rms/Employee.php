<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'last_name',
        'f_name',
        'g_f_name',
        'email',
        'phone',
        'none_criminal_record',
        'none_criminal_record_info',
        'country',
        'type_residence_info',
        'main_province',
        'main_district',
        'main_village',
        'current_province',
        'current_district',
        'current_village',
    ];
}
