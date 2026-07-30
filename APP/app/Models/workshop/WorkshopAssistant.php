<?php

namespace App\Models\workshop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WorkshopAssistant extends Model
{

    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([

                'name_dr',
                'name_en',
                'last_name_dr',
                'last_name_en',
                'f_name_da',
                'email',
                'phone',
                'passport_no',
                'country',
                'type_residence_info',
                'photo',
                'main_province',
                'main_district',
                'main_village',
                'current_province',
                'current_district',
                'current_village',
                'status',
                'reason_dismissed',
                'created_by',
                'created_department',
                'created_location',
            ])->logOnlyDirty(true)->useLogName('WorkshopAssistant');
    }
    protected $fillable = [
        'company_id',
        'name_dr',
        'name_en',
        'last_name_dr',
        'last_name_en',
        'f_name_da',
        'email',
        'phone',
        'passport_no',
        'country',
        'type_residence_info',
        'photo',
        'main_province',
        'main_district',
        'main_village',
        'current_province',
        'current_district',
        'current_village',
        'status',
        'reason_dismissed',
        'created_by',
        'created_department',
        'created_location',
    ];
    public $timestamps = true;
}
