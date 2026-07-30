<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Gun extends Model
{
    use HasFactory, LogsActivity;



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'gun_type',
                'gun_no',
                'gun_diameter',
                'taedad_jabeh',
                'gun_country',
                'weapon_id',
                'created_department',
                'created_location',
                'created_by',

            ])->logOnlyDirty(true)->useLogName('Gun');
    }

    protected $fillable = [
        'gun_type',
        'gun_no',
        'gun_diameter',
        'taedad_jabeh',
        'gun_country',
        'weapon_id',
        'company_id',
        'created_by',
        'created_department',
        'created_location',
    ];
}
