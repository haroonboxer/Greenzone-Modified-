<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssistantResource extends JsonResource
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
            'name_dr' => $this->name_dr,
            'name_en' => $this->name_en,
            'last_name_dr' => $this->last_name_dr,
            'last_name_en' => $this->last_name_en,
            'f_name_da' => $this->f_name_da,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'reason_dismissed' => $this->reason_dismissed,
            'passport_no' => $this->passport_no,
            'country' => $this->country,
            'type_residence_info' => $this->type_residence_info,
            'mainProvince' => $this->mainProvince,
            'mainDistrict' => $this->mainDistrict,
            'main_village' => $this->main_village,
            'currentProvince' => $this->currentProvince,
            'currentDistrict' => $this->currentDistrict,
            'current_village' => $this->current_village,
            'attchments' =>  $this->attachment ? asset($this->attachment) : null,
            'photo' =>  $this->photo ? asset($this->photo) : null,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
            'createdLocation' => $this->createdLocation,
            'createdDepartment' => $this->createdDepartment,
        ];
    }
}
