<?php

namespace App\Http\Requests\FeedSale;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('sale-feeds');
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
            'location_id' => 'bail|required|exists:locations,id',
            'sale_date' => 'bail|required|date',
            'feeds' => 'bail|required|array',
            'feeds.*.feed_id' => 'bail|required|exists:feeds,id',
            'feeds.*.price_per_unit' => 'bail|required|numeric|min:0',
            'feeds.*.qty' => 'bail|required|numeric|min:0',
            'created_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'location_id.required' => 'Location ID is required.',
            'location_id.exists' => 'The selected location does not exist.',
            'sale_date.required' => 'Sale date is required.',
            'sale_date.date' => 'Sale date must be a valid date.',
            'feeds.required' => 'Feed data is required.',
            'feeds.array' => 'Feed data must be an array.',
        ];
    }   
    public function getData(): array
    {
        return $this->only([
            'location_id',
            'sale_date',
            'feeds',
            'created_by',
        ]);
    }
}
