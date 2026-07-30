<?php

namespace App\Http\Resources\green_zone;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleSaveResource extends JsonResource
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
            'name' => $this->name,
            'created_by' => $this->created_by,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i') : null,
            'createdDepartment' => $this->createdDepartment,
            'createdLocation' => $this->createdLocation,
        ];
    }
}
