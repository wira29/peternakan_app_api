<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-materials');
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
            'stock' => [
                'bail',
                'required',
                'integer',
                'min:0',
            ],
            'unit' => [
                'bail',
                'required',
                'string',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Material name is required.',
            'name.string' => 'Material name must be a string.',
            'name.max' => 'Material name must not exceed 255 characters.',
            'stock.required' => 'Stock is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock must be at least 0.',
            'unit.required' => 'Unit is required.',
            'unit.string' => 'Unit must be a string.',
            'unit.max' => 'Unit must not exceed 100 characters.',
        ];
    }
    public function getData(): array
    {
        return $this->only([
            'name',
            'stock',
            'unit',
            'created_by',
        ]);
    }
}
