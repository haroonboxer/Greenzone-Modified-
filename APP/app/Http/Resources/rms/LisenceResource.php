<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LisenceResource extends JsonResource
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
            'license_date' => $this->license_date,
            'slip_no' => $this->slip_no,
            'fee' => $this->fee,
            'status' => $this->status,
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
