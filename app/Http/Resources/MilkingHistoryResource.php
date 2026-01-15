<?php

namespace App\Http\Resources;

use App\Http\Resources\GoatResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkingHistoryResource extends JsonResource
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
            'goat' => new GoatResource($this->goat),
            'qty' => $this->qty,
            'milked_at' => $this->milked_at,
            'created_at' => $this->created_at,
            'created_by' => $this->createdBy?->name,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updatedBy?->name,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deletedBy?->name,
        ];
    }
}
