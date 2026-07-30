<?php

namespace App\Http\Resources\green_zone;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class driverResource extends JsonResource
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
            'f_name' => $this->f_name,
            'g_f_name' => $this->g_f_name,
            'phone' => $this->phone,
            'photo' => $this->photo,
            'status' => $this->status,
            'reason_dismissed' => $this->reason_dismissed,
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
