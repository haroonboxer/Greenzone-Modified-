<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GunResource extends JsonResource
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
            'gun_country' => $this->gun_country,
            'gun_no' => $this->gun_no,
            'gun_type' => $this->gun_type,
            'gun_diameter' => $this->gun_diameter,
            'taedad_jabeh' => $this->taedad_jabeh,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
            'createdLocation' => $this->createdLocation,
            'createdDepartment' => $this->created_department
        ];
    }
}
