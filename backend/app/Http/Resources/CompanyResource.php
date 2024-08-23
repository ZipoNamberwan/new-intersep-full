<?php

namespace App\Http\Resources;

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
            'id_sbr' => $this->id_sbr,
            'name' => $this->name,
            'kab' => $this->kab,
            'kab_name' => $this->kab_name,
            'kec' => $this->kec,
            'kec_name' => $this->kec_name,
            'des' => $this->des,
            'des_name' => $this->des_name,
            'address' => $this->address,
            'coordinate' => $this->coordinate,
            'subsectors' => SubsectorResource::collection($this->whenLoaded('subsectors')),
            'surveys' => SurveyResource::collection($this->whenLoaded('surveys')),
        ];
    }
}
