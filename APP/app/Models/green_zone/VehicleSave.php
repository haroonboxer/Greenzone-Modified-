<?php

namespace App\Models\green_zone;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleSave extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
    ];
}
