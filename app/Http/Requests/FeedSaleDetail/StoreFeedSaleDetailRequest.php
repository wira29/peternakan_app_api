<?php

namespace App\Http\Requests\FeedSaleDetail;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedSaleDetailRequest extends FormRequest
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
            'feed_sale_id' => 'bail|required|exists:feed_sales,id',
            'feed_id' => 'bail|required|exists:feeds,id',
            'qty' => 'bail|required|numeric|min:0',
            'price_per_unit' => 'bail|required|numeric|min:0',
            'created_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'feed_sale_id.required' => 'Feed Sale ID is required.',
            'feed_sale_id.exists' => 'The selected feed sale does not exist.',
            'feed_id.required' => 'Feed ID is required.',
            'feed_id.exists' => 'The selected feed does not exist.',
            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a number.',
            'qty.min' => 'Quantity must be at least 0.',
            'price_per_unit.required' => 'Price per unit is required.',
            'price_per_unit.numeric' => 'Price per unit must be a number.',
            'price_per_unit.min' => 'Price per unit must be at least 0.',
            'created_by.required' => 'Creator ID is required.',
            'created_by.exists' => 'The selected creator does not exist.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'feed_sale_id',
            'feed_id',
            'qty',
            'price_per_unit',
            'created_by',
        ]);
    }
}
