<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialTransactionResource extends JsonResource
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
            'supplier' => $this->supplier,
            'total' => $this->total,
            'transaction_date' => $this->transaction_date,
            'materials' => MaterialTransactionDetailResource::collection($this->details),
            'created_by' => $this->createdBy?->name,
            'updated_by' => $this->updatedBy?->name,
            'deleted_by' => $this->deletedBy?->name,
        ];
    }
}
