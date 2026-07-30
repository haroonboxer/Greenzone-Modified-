<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Company extends Model
{
    use HasFactory, LogsActivity;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'company_pa',
                'company_dr',
                'company_en',
                'icon',
                'haq_alamatyaz',
                'hanging_date',
                'bank_account_number',
                'amount_of_money',
                'status',
                'reason_dismissed',
                'created_by',
                'created_department',
                'created_location',
            ])->logOnlyDirty(true)->useLogName('companies');
    }

    protected $fillable = [
        'company_dr',
        'company_pa',
        'company_en',
        'icon',
        'status',
        '',
        '',
    ];
    public $timestamps = true;
}
