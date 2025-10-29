<?php

namespace App\Http\Requests\Breed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBreedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-breeds');
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
            'name' => 'bail|required|string|max:255',
            'remarks' => 'bail|nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Breed name is required.',
            'name.string' => 'Breed name must be a string.',
            'name.max' => 'Breed name must not exceed 255 characters.',
            'remarks.string' => 'Remarks must be a string.',
            'remarks.max' => 'Remarks must not exceed 500 characters.',
        ];
    }

    public function getData(): array
    {
        return $this->only(['name', 'remarks', 'created_by']);
    }
}
