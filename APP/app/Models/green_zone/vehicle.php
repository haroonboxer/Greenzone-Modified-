<?php

namespace App\Models\green_zone;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicle extends Model
{
    use HasFactory;
    public function type()
    {
        return $this->belongsTo(VehicleSave::class, 'vehicle_type');
    }
    protected $casts = [
        'vehicle_type' => 'integer',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function licenses()
    {
        return $this->hasMany(License::class, 'vehicle_id');
    }
    protected $fillable = [
        "vehicle_type",
        "vehicle_color",
        "vehicle_platte_no",
        "vehicle_engine_no",
        "vehicle_source",
        "front_photo",
        "back_photo",
        "plate_photo",
    ];
}
