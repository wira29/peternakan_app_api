<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CageResource extends JsonResource
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
            'location' => $this->location?->location,
            'remarks' => $this->remarks,
            'created_by' => $this->createdBy?->name,
            'updated_by' => $this->updatedBy?->name,
        ];
    }
}
