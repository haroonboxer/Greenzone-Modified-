<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehical extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehical_type',
        'vehical_ownership',
        'vehical_platte_no',
        'vehical_color',
        'engine_no',
        'shasi_no',
        'license_start_date',
        'license_end_date',
    ];
}
