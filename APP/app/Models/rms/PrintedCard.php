<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintedCard extends Model
{
    use HasFactory;
    protected $fillable = [

        'weapons',
        'card_type',
        'project_name_dr',
        'project_name_en',
        'card_perimeter_dr',
        'card_perimeter_en',
        'issued_date',
        'expire_date',

    ];
}
