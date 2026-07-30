<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintedCardResource extends JsonResource
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
            'weapons' => $this->weapons,
            'card_type' => $this->card_type, // Will return 'new' or 'extend'
            'project_name_dr' => $this->project_name_dr,
            'project_name_en' => $this->project_name_en,
            'card_perimeter_dr' => $this->card_perimeter_dr,
            'card_perimeter_en' => $this->card_perimeter_en,
            'issued_date' => $this->issued_date,
            'expire_date' => $this->expire_date,
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
