<?php

namespace App\Http\Resources\workshop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class workshopBossResource extends JsonResource
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
            'phone' => $this->phone,
            'photo' => $this->photo,
            'status' => $this->status,
            'passport_no' => $this->passport_no,
            'country' => $this->country,
            'main_province' => $this->main_province,
            'main_district' => $this->main_district,
            'main_village' => $this->main_village,
            'current_province' => $this->current_province,
            'current_district' => $this->current_district,
            'current_village' => $this->current_village,
            'type_residence_info' => $this->type_residence_info,
            'attchments' =>  $this->attachment ? asset($this->attachment) : null,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i') : null,

        ];
    }
}
