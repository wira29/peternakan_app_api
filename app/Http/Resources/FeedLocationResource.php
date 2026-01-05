<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedLocationResource extends JsonResource
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
            'location' => $this->location?->location,
            'name' => $this->name,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'created_by' => $this->createdBy?->name,
            'created_at' => $this->created_at,
            'updated_by' => $this->updatedBy?->name,
            'updated_at' => $this->updated_at,
            'deleted_by' => $this->deletedBy?->name,    
            'deleted_at' => $this->deleted_at
        ];
    }
}
