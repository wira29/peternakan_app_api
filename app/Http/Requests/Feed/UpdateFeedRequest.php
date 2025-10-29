<?php

namespace App\Http\Requests\Feed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-feeds');
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
            'name' => 'bail|required|string|max:255|sometimes',
            'stock' => 'bail|required|integer|min:0|sometimes',
            'unit' => 'bail|required|string|max:100|sometimes',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Feed name must be a string.',
            'name.max' => 'Feed name must not exceed 255 characters.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock must be at least 0.',
            'unit.string' => 'Unit must be a string.',
            'unit.max' => 'Unit must not exceed 100 characters.',
        ];
    }

    public function getData(): array
    {
        return $this->only(['name', 'stock', 'unit', 'updated_by']);
    }
}
