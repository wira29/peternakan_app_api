<?php

namespace App\Http\Requests\WeightHistory;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeightHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view-weight-records');
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
            'goat_code' => [
                'bail',
                'required',
                'exists:goats,code',
            ],
            'weight' => [
                'bail',
                'required',
                'integer',
                'min:0',
            ],
            'height' => [
                'bail',
                'nullable',
                'integer',
                'min:0',
            ],
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
            'goat_code.required' => 'Goat code is required.',
            'goat_code.exists' => 'The selected goat code is invalid.',
            'weight.required' => 'Weight is required.',
            'weight.integer' => 'Weight must be an integer.',
            'weight.min' => 'Weight must be at least 0.',
            'height.integer' => 'Height must be an integer.',
            'height.min' => 'Height must be at least 0.',
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a valid date.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'goat_code',
            'weight',
            'height',
            'date',
            'created_by',
        ]);
    }
}
