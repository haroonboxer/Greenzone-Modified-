<?php

namespace App\Http\Resources\green_zone;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'license_type'   => $this->license_type,
            'issue_date'     => $this->issue_date,
            'expire_date'    => $this->expire_date,
            'sn'             => $this->sn,
            'status'         => $this->status,
            'printed'        => $this->printed,

            // Relations (from joins)
            'vehicle' => [
                'id'         => $this->vehicle_id,
                'type'       => $this->vehicle_type,
                'type_name'   => $this->vehicle_type_name,
                'color'      => $this->vehicle_color,
                'source'      => $this->vehicle_source,
                'plate_no'   => $this->vehicle_platte_no,
                'front_photo' => $this->front_photo,
            ],
            'driver' => [
                'id'         => $this->driver_id,
                'name'       => $this->driver_name,
                'photo'      => $this->driver_photo,
            ],
            'created_by' => [
                'id'   => $this->created_by,
                'name' => $this->created_by, // fallback if no join
                'user' => $this->when(isset($this->created_by), $this->created_by),
            ],
            'createdLocation'   => $this->when(isset($this->createdLocation), $this->createdLocation),
            'createdDepartment' => $this->when(isset($this->createdDepartment), $this->createdDepartment),
        ];
    }
}
