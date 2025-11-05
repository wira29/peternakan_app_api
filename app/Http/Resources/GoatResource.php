<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'breed' => $this->breed?->name,
            'cage' => $this->cage?->name,
            'father' => $this->father?->code ?? $this->father?->name,
            'mother' => $this->mother?->code ?? $this->mother?->name,
            'origin' => $this->origin?->value,
        ];
    }
}

