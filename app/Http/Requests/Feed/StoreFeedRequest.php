<?php

namespace App\Http\Requests\Feed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-feeds');
    }

    public function prepareForValidation(): void
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
                Rule::unique('feeds')->where(function ($query) {
                    return $query->where('unit', $this->unit);
                }),
            ],
            'stock' => 'bail|required|integer|min:0',
            'unit' => 'bail|required|string|max:100',
            'price' => 'bail|nullable|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Feed name is required.',
            'name.string' => 'Feed name must be a string.',
            'name.max' => 'Feed name must not exceed 255 characters.',
            'name.unique' => 'The combination of feed name and unit must be unique.',
            'stock.required' => 'Stock is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock must be at least 0.',
            'unit.required' => 'Unit is required.',
            'unit.string' => 'Unit must be a string.',
            'unit.max' => 'Unit must not exceed 100 characters.',
            'price.integer' => 'Price must be an integer.',
            'price.min' => 'Price must be at least 0.',

        ];
    }

    public function getData(): array
    {
        return $this->only(['name', 'stock', 'unit', 'price', 'created_by']);
    }
}
