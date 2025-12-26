<?php

namespace App\Http\Requests\Vaccine;

use Illuminate\Foundation\Http\FormRequest;

class StoreVaccineRequest extends FormRequest
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
            'name' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
            
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vaccine name is required.',
            'name.string' => 'Vaccine name must be a string.',
            'name.max' => 'Vaccine name must not exceed 255 characters.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'name',
            'created_by',
        ]);
    }
}
