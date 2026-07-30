<?php

namespace App\Models\rms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WeaponsGeneralTable extends Model
{
    use HasFactory, LogsActivity;



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'number_of_weapons',
                'slip_no',
                'money_amount',
                'slip_date',
                'status',
                'company_id',
                'created_by',
                'created_department',
                'created_location',
            ])->logOnlyDirty(true)->useLogName('Weapons General');
    }

    protected $fillable = [
        'number_of_weapons',
        'slip_no',
        'money_amount',
        'slip_date',
        'status',
        'company_id',
        'created_by',
        'created_department',
        'created_location',
    ];
}
