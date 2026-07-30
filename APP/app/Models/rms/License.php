<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;
    protected $fillable = [
        'license_type',
        'issue_date',
        'validity_date',
        'license_date',
        'slip_no',
        'fee',
        'status',
    ];
}
