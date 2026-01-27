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
        $vaccineLimit = $request->input('limit_vaccine', 5);
        $matingLimit  = $request->input('limit_mating', 5);
        $weightLimit  = $request->input('limit_weight', 5);
        $milkingLimit  = $request->input('limit_milking', 5);

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
            'vaccines' => VaccineHistoryResource::collection(
                $this->whenLoaded('vaccineHistories', function () use ($vaccineLimit) {
                    return $vaccineLimit ? $this->vaccineHistories->take((int)$vaccineLimit) : $this->vaccineHistories;
                })
            ),
            'mating_history' => MatingHistoryResource::collection(
                $this->whenLoaded('matingHistory', function () use ($matingLimit) {
                    return $matingLimit ? $this->matingHistory->take((int)$matingLimit) : $this->matingHistory;
                })
            ),
            'weight_history' => WeightHistoryResource::collection(
                $this->whenLoaded(
                    'weightHistories',
                    function () use ($weightLimit) {
                        return $weightLimit ? $this->weightHistories->take((int)$weightLimit) : $this->weightHistories;
                    }
                )
            ),
            'milked_histories' => MilkingHistoryResource::collection(
                $this->whenLoaded('milkingHistories', function () use ($milkingLimit) {
                    return $milkingLimit ? $this->milkingHistories->take((int)$milkingLimit) : $this->milkingHistories;
                })
            ),
            'created_by' => $this->createdBy?->name,
            'updated_by' => $this->updatedBy?->name,
            'deleted_by' => $this->deletedBy?->name,
        ];
    }
}
