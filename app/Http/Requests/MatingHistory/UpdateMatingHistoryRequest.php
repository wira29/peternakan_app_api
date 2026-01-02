<?php

namespace App\Http\Requests\MatingHistory;

use App\Enums\MatingStatusEnum;
use App\Enums\MatingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMatingHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view-mating-records');
    }

    public function prepareForValidation()
    {
        $this->merge([
            'updated_by' => auth()->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'male_id' => 'bail|sometimes|string|exists:goats,code',
            'female_id' => 'bail|sometimes|string|exists:goats,code',
            'mating_type' => [
                            'bail',
                            'sometimes',
                            'string',                         
                            new Enum(MatingTypeEnum::class)
                        ],
            'status' => [
                            'bail',
                            'sometimes',
                            'string',
                            new Enum(MatingStatusEnum::class)
                        ],
            'mating_date' => 'bail|sometimes|nullable|date',
            'remark' => 'bail|sometimes|nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'male_id.exists' => 'Male ID must exist in goats.', 
            'female_id.exists' => 'Female ID must exist in goats.', 
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'male_id',  
            'female_id',
            'mating_type',
            'status',
            'mating_date',
            'remark',
            'updated_by',
        ]);
    }
}
