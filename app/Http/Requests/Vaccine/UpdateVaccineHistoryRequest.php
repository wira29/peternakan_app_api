<?php

namespace App\Http\Requests\Vaccine;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVaccineHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view-vaccine-records');
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $data = $this->json()->all();
        $data['updated_by'] = auth()->user()->id;
        $this->replace($data);

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
                'sometimes',
                'string',
                'exists:vaccines,id',
            ],
            'goat_code' => [
                'bail',
                'sometimes',
                'string',
                'exists:goats,code',
            ],
            'date' => [
                'bail',
                'sometimes',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'vaccine_id.string' => 'Vaccine ID must be a string.',
            'vaccine_id.exists' => 'The selected vaccine ID is invalid.',
            'goat_code.string' => 'Goat code must be a string.',
            'goat_code.exists' => 'The selected goat code is invalid.',
            'date.date' => 'Date must be a valid date.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'vaccine_id',
            'goat_code',
            'date',
            'updated_by',
        ]);
    }
}
