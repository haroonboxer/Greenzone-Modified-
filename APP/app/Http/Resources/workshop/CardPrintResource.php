<?php

namespace App\Http\Resources\workshop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardPrintResource extends JsonResource
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
            'license_type' => $this->license_type,
            'issue_date' => $this->issue_date,
            'validity_date' => $this->validity_date,
            'fee' => $this->fee,
            'company_name_dr' => $this->company_name_dr,
            'boss_name_dr' => $this->boss_name_dr,
            'assistant_name_dr' => $this->assistant_name_dr,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
        ];
    }
}
