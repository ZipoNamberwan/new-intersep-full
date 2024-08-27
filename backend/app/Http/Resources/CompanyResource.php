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
            'id' => $this->id,
            'id_sbr' => $this->id_sbr,
            'name' => $this->name,
            'kab' => $this->kab,
            'kec' => $this->kec,
            'des' => $this->des,
            'bs' => $this->bs,
            'address' => $this->address,
            'coordinate' => $this->coordinate,
            'subsectors' => SubsectorResource::collection($this->whenLoaded('subsectors')),
            'surveys' => SurveyResource::collection($this->whenLoaded('surveys')),
        ];
    }
}
