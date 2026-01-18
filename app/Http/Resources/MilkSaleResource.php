<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkSaleResource extends JsonResource
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
            'location' => $this->location->location,
            'sale_date' => $this->sale_date,
            'qty' => $this->qty,
            'price_per_liter' => $this->price_per_liter,
            'total' => $this->total,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'created_by' => $this->createdBy?->name,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updatedBy?->name,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deletedBy?->name,
        ];
    }
}
