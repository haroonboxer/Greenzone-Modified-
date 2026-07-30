<?php

namespace App\Http\Resources\green_zone;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class vehicleReource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_type_name' => $this->type?->name,
            'vehicle_color' => $this->vehicle_color,
            'vehicle_platte_no' => $this->vehicle_platte_no,
            'vehicle_engine_no' => $this->vehicle_engine_no,
            'vehicle_source' => $this->vehicle_source,
            'status' => $this->status,
            'front_photo' => $this->front_photo ? asset($this->front_photo) : null,
            'back_photo' => $this->back_photo ? asset($this->back_photo) : null,
            'plate_photo' => $this->plate_photo ? asset($this->plate_photo) : null,
            'created_by' => $this->created_by,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'driverName' => $this->when(isset($this->driverName), $this->driverName),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i') : null,
            'createdDepartment' => $this->createdDepartment,
            'createdLocation' => $this->createdLocation,
        ];
    }
}
