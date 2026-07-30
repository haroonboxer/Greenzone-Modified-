<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'last_name' => $this->last_name,
            'f_name' => $this->f_name,
            'g_f_name' => $this->g_f_name,
            'phone' => $this->phone,
            'none_criminal_record_info' => $this->none_criminal_record,
            'none_criminal_record_info' => $this->none_criminal_record_info,
            'country' => $this->country,
            'type_residence_info' => $this->type_residence_info,
            'status' => $this->status,
            'none_criminal_record' => $this->none_criminal_record,
            'none_criminal_record_info' => $this->none_criminal_record_info,
            'type_residence_info' => $this->type_residence_info,
            'main_province' => $this->mainProvince,
            'main_district' => $this->mainDistrict,
            'main_village' => $this->main_village,
            'current_province' => $this->currentProvince,
            'current_district' => $this->currentDistrict,
            'current_village' => $this->current_village,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'createdDepartment' => $this->createdDepartment,
            'createdLocation' => $this->createdLocation,
            'attachments' => $this->attachment ? asset($this->attachment) : null, // only if "attachment" exists in model
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
        ];
    }
}
