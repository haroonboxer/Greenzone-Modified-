<?php

namespace App\Http\Resources\workshop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company_pa' => $this->company_pa,
            'company_dr' => $this->company_dr,
            'company_en' => $this->company_en,
            'address' => $this->address,
            'tin' => $this->tin,
            'icon' => $this->icon ? asset($this->icon) : null,
            'status' => (int) $this->status,
            'attchments' =>  $this->attachment ? asset($this->attachment) : null,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
        ];
    }
}
