<?php

namespace App\Http\Requests\Feeding;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('feeding');
    }

    public function prepareForValidation() : void
    {
        $user = auth()->user();
        $this->merge([
            'created_by' => $user->id,
            'date' => now(),
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
            'cage_id' => 'required|exists:cages,id',
            'feed_location_id' => 'required|exists:feed_locations,id',
            'qty' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'cage_id.required' => 'Cage ID is required.',
            'cage_id.exists' => 'The selected cage does not exist.',
            'feed_location.required' => 'Feed location is required.',
            'feed_location.string' => 'Feed location must be a string.',
            'feed_location.max' => 'Feed location must not exceed 255 characters.',
            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a numeric value.',
        ];
    }

    public function getData(): array
    {
        return $this->only(['cage_id', 'feed_location_id', 'qty', 'date', 'created_by']);
    }
}
