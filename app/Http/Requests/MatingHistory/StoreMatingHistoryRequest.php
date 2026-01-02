<?php

namespace App\Http\Requests\MatingHistory;

use App\Enums\MatingStatusEnum;
use App\Enums\MatingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreMatingHistoryRequest extends FormRequest
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
            'created_by' => auth()->user()->id,
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
            'male_id' => 'bail|required|string|exists:goats,code',
            'female_id' => 'bail|required|string|exists:goats,code',
            'mating_type' => [
                            'bail',
                            'required',
                            'string',                         
                            new Enum(MatingTypeEnum::class)
                        ],
            'status' => [
                            'bail',
                            'required',
                            'string',
                            new Enum(MatingStatusEnum::class)
                        ],
            'mating_date' => 'bail|nullable|date',
            'remark' => 'bail|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'male_id.required' => 'Male ID is required.',
            'female_id.required' => 'Female ID is required.',
            'mating_type.required' => 'Mating type is required.',
            'status.required' => 'Status is required.',
            'mating_date.date' => 'Mating date must be a valid date.',
            'remark.string' => 'Remark must be a string.',
            
        ];
    }

    public function getData()
    {
        return [
            'male_id' => $this->male_id,
            'female_id' => $this->female_id,
            'mating_type' => $this->mating_type,
            'status' => $this->status,
            'mating_date' => $this->mating_date,
            'remark' => $this->remark,
            'created_by' => $this->created_by,
        ];
    }
}
