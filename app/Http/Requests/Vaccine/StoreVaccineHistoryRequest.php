<?php

namespace App\Http\Requests\Vaccine;

use Illuminate\Foundation\Http\FormRequest;

class StoreVaccineHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view-vaccine-records');
    }

    public function prepareForValidation() : void
    {
        $user = auth()->user();
        $this->merge([
            'created_by' => $user->id,
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
            'vaccine_id' => [
                'bail',
                'required',
                'string',
                'exists:vaccines,id',
            ],
            'goat_code' => [
                'bail',
                'sometimes',
                'nullable',
                'string',
                'exists:goats,code',
            ],
            'cage_id' => [
                'bail',
                'sometimes',
                'array',
            ],
            'cage_id.*' => 'uuid|exists:cages,id',
            'date' => [
                'bail',
                'required',
                'date',
            ],

        ];
    }
    public function messages(): array
    {
        return [
            'vaccine_id.required' => 'Vaccine ID is required.',
            'vaccine_id.string' => 'Vaccine ID must be a string.',
            'vaccine_id.exists' => 'The selected vaccine does not exist.',
            'goat_code.required' => 'Goat code is required.',
            'goat_code.string' => 'Goat code must be a string.',
            'goat_code.exists' => 'The selected goat does not exist.',
            'cage_id.array' => 'Cage ID must be an array.',
            'cage_id.exists' => 'One or more selected cages do not exist.',
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a valid date.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'vaccine_id',
            'goat_code',
            'cage_id',
            'date',
            'created_by',
        ]);
    }
}
