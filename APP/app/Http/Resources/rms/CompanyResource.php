<?php

namespace App\Http\Resources\rms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'haq_alamatyaz' => $this->haq_alamatyaz,
            'date_of_issue' => $this->date_of_issue,
            'date_of_validity' => $this->date_of_validity,
            'hanging_date' => $this->hanging_date,
            'bank_account_number' => $this->bank_account_number,
            'amount_of_money' => $this->amount_of_money,
            'icon' => $this->icon ? asset($this->icon) : null,
            'status' => (int) $this->status,
            'attchments' =>  $this->attachment ? asset($this->attachment) : null,
            'ownerName' => $this->when(isset($this->ownerName), $this->ownerName),
            'created_at' => dateCheck($this->created_at, true),
        ];
    }
}
