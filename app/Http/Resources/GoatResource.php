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
            'location' => $this->location?->name,
            'father' => $this->father?->code ?? $this->father?->name,
            'mother' => $this->mother?->code ?? $this->mother?->name,
            'origin' => $this->origin?->value,
            'color' => $this->color?->name,
            'gender' => $this->gender?->value,
            'date' => $this->date,
            'price' => $this->price,    
            'is_breeder' => $this->is_breeder,
            'is_qurbani' => $this->is_qurbani,
            'remarks' => $this->remarks,
            'created_by' => $this->createdBy?->name,
            'updated_by' => $this->updatedBy?->name,
            'deleted_by' => $this->deletedBy?->name,

        ];
    }
}

