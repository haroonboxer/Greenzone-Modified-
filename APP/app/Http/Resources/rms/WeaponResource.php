<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeaponResource extends JsonResource
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
            'number_of_weapons' => $this->number_of_weapons,
            'slip_no' => $this->slip_no,
            'money_amount' => $this->money_amount,
            'reason_dismissed' => $this->reason_dismissed,
            'status' => $this->status,
            'slip_date' => dateCheck($this->slip_date, true),
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
            'createdLocation' => $this->createdLocation,
            'createdDepartment' => $this->created_department,
        ];
    }
}
