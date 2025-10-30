<?php

namespace App\Http\Requests\Cage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-cages');
    }

    public function prepareForValidation() : void
    {
        $data = $this->json()->all();
        $data['updated_by'] = Auth::user()->id;
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
            'name' => 'bail|sometimes|string|max:255',
            'location_id' => 'bail|sometimes|exists:locations,id',
            'capacity' => 'bail|sometimes|integer|min:1',
            'remarks' => 'bail|sometimes|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Cage name must be a string.',
            'name.max' => 'Cage name must not exceed 255 characters.',
            'location_id.exists' => 'The selected location does not exist.',
            'capacity.integer' => 'Capacity must be an integer.',
            'capacity.min' => 'Capacity must be at least 1.',
            'remarks.string' => 'Remarks must be a string.',
            'remarks.max' => 'Remarks must not exceed 500 characters.',
        ];
    }
    public function getData(): array
    {
        return $this->only(['name', 'location_id', 'capacity', 'remarks', 'updated_by']);
    }
}
