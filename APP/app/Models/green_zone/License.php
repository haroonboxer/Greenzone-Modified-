<?php

namespace App\Models\green_zone;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class License extends Model
{
    protected $table = 'gzlicenses';
    use HasFactory;

    public function vehicle()
    {
        return $this->belongsTo(vehicle::class, 'vehicle_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    protected $fillable = [
        "license_type",
        "issue_date",
        "expire_date",
        "sn",
        "reject_reason",
        "status",
        "printed",
        "sent",
    ];
}
