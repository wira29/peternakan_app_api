<?php

namespace App\Http\Requests\FeedSaleDetail;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedSaleDetailRequest extends FormRequest
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
            'feed_sale_id' => 'bail|sometimes|exists:feed_sales,id',
            'feed_id' => 'bail|sometimes|exists:feeds,id',
            'qty' => 'bail|sometimes|numeric|min:0',
            'price_per_unit' => 'bail|sometimes|numeric|min:0',
            'updated_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'feed_sale_id.exists' => 'The selected feed sale does not exist.',
            'feed_id.exists' => 'The selected feed does not exist.',
            'qty.numeric' => 'Quantity must be a number.',
            'qty.min' => 'Quantity must be at least 0.',
            'price_per_unit.numeric' => 'Price per unit must be a number.',
            'price_per_unit.min' => 'Price per unit must be at least 0.',
            'updated_by.required' => 'Updater ID is required.',
            'updated_by.exists' => 'The selected updater does not exist.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'feed_sale_id',
            'feed_id',
            'qty',
            'price_per_unit',
            'updated_by',
        ]);
    }
}
