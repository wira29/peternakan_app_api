<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeightHistoryResource extends JsonResource
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
            'goat' => new GoatResource($this->whenLoaded('goat')),
            'goat_code' => $this->goat_code,
            'weight' => $this->weight,
            'height' => $this->height,
            'date' => $this->date,
            'created_by' => $this->createdBy?->name,
            'updated_by' => $this->updatedBy?->name,
            'deleted_by' => $this->deletedBy?->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
