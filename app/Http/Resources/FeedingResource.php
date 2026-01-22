<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedingResource extends JsonResource
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
            'cage_id' => $this->cage?->id,
            'cage' => $this->cage?->name,
            'feed_location_id' => $this->feedLocation?->id,
            'feed_location' => $this->feedLocation?->name,
            'feed_unit' => $this->feedLocation?->unit,
            'qty' => $this->qty,
            'date' => $this->date,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}
