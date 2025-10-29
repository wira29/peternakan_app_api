<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLocationRequest extends FormRequest
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
                'location' => [
                    'bail',
                    'string',
                    'max:255',
                    'sometimes'
                ],
                'image' => [
                    'bail',
                    'string',
                    'max:255',
                    'sometimes'
                ],
        ];
    }

    public function messages(): array
    {
        return [
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
            'updated_by',
        ]);
    }
}
