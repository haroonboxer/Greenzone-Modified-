<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_source',
        'contract_location',
        'contract_start_date',
        'contract_end_date',
        'afghan_personal_count',
        'external_personal_count',
        'ammo_count',
        'vehical_count',
        'walkie_talkie_count',
        'equipments_value',
        'other_equipments',
        'status'
    ];
}
