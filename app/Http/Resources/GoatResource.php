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
            'location' => $this->location?->location,
            'father' => $this->father?->code,
            'mother' => $this->mother?->code,
            'origin' => $this->origin?->value,
            'color' => $this->color,
            'gender' => $this->gender?->value,
            'date_of_birth' => $this->date_of_birth,
            'date_of_purchase' => $this->date_of_purchase,
            'price' => $this->price,    
            'is_breeder' => $this->is_breeder ? true : false,
            'is_qurbani' => $this->is_qurbani ? true : false,
            'remarks' => $this->remarks,
            'created_by' => $this->createdBy?->name,
            'updated_by' => $this->updatedBy?->name,
            'deleted_by' => $this->deletedBy?->name,
        ];
    }
}

