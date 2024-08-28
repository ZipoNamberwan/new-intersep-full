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
            'kab' => new KabupatenResource($this->whenLoaded('kab')),
            'kec' => new KabupatenResource($this->whenLoaded('kec')),
            'des' => new KabupatenResource($this->whenLoaded('des')),
            'bs' => new KabupatenResource($this->whenLoaded('bs')),
            'address' => $this->address,
            'ycoordinate' => $this->ycoordinate,
            'xcoordinate' => $this->xcoordinate,
            'subsectors' => SubsectorResource::collection($this->whenLoaded('subsectors')),
            'surveys' => SurveyResource::collection($this->whenLoaded('surveys')),
        ];
    }
}
