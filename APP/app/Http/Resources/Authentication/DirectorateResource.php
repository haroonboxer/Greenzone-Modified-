<?php

namespace App\Http\Resources\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DirectorateResource extends JsonResource
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
            'name_en' => $this->name_en,
            'code' => $this->code,
            'ownerName' => $this->ownerName,
        ];
    }
}
