<?php

namespace App\Http\Resources\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'name_da' => $this->name_da,
            'name_pa' => $this->name_pa,
            'directorateName' => $this->directorateName,
            'ownerName' => $this->ownerName,
        ];
    }
}
