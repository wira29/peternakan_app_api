<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-locations');
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
            'location' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
            'image' => [
                'bail',
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'location.required' => 'Location is required.',
            'location.string' => 'Location must be a string.',
            'location.max' => 'Location must not exceed 255 characters.',
            'image.string' => 'Image must be a string.',
            'image.max' => 'Image must not exceed 255 characters.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'location',
            'image',
            'created_by',
        ]);
    }
}
