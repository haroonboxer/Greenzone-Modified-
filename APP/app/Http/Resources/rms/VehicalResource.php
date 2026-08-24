<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicalResource extends JsonResource
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
            'vehical_type' => $this->vehical_type,
            'vehical_ownership' => $this->vehical_ownership,
            'vehical_platte_no' => $this->vehical_platte_no,
            'vehical_color' => $this->vehical_color,
            'engine_no' => $this->engine_no,
            'shasi_no' => $this->shasi_no,
            'license_start_date' => $this->license_start_date,
            'license_end_date' => $this->license_end_date,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            // 'createdDepartment' => $this->createdDepartment,
            // 'createdLocation' => $this->createdLocation,
            'attachments' => $this->attachment ? asset($this->attachment) : null, // only if "attachment" exists in model
            'ownerName' => $this->create_by_name, //$this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
        ];
    }
}
