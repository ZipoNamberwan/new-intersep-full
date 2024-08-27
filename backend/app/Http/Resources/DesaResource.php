<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesaResource extends JsonResource
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
            'name' => $this->name,
            'short_code' => $this->short_code,
            'long_code' => $this->long_code,
            'klas' => $this->klas,
            'kec' => new KecamatanResource($this->whenLoaded('kec')),
        ];
    }
}
