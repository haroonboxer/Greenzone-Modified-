<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
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
            'contract_source' => $this->contract_source,
            'contract_location' => $this->contract_location,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'afghan_personal_count' => $this->afghan_personal_count,
            'external_personal_count' => $this->external_personal_count,
            'ammo_count' => $this->ammo_count,
            'vehical_count' => $this->vehical_count,
            'walkie_talkie_count' => $this->walkie_talkie_count,
            'equipments_value' => $this->equipments_value,
            'other_equipments' => $this->other_equipments,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'createdDepartment' => $this->createdDepartment,
            'createdLocation' => $this->createdLocation,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
        ];
    }
}
