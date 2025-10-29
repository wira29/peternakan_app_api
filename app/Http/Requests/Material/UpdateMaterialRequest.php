<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMaterialRequest extends FormRequest
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
            'name' => [
                'bail',
                'string',
                'max:255',
                'sometimes'
            ],
            'stock' => [
                'bail',
                'integer',
                'min:0',
                'sometimes'
            ],
            'unit' => [
                'bail',
                'string',
                'max:100',
                'sometimes'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Material name must be a string.',
            'name.max' => 'Material name must not exceed 255 characters.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock must be at least 0.',
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
            'updated_by',
        ]);
    }
}
