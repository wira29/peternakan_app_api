<?php

namespace App\Http\Requests\MilkSale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMilkSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-milk');
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'qty' => (int) $this->qty,
            'updated_by' => auth()->user()->id
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
            'sale_date' => ['bail','sometimes', 'date'],
            'qty' => ['bail','sometimes', 'integer', 'min:0'],
            'price_per_liter' => ['bail','sometimes', 'integer', 'min:0'],
            'total' => ['bail','sometimes', 'integer', 'min:0'],
            'remark' => ['bail','sometimes', 'string'],
            'updated_by' => ['bail','required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'qty.min' => 'Quantity must be greater than or equal to 0',
            'price_per_liter.min' => 'Price per liter must be greater than or equal to 0',
            'total.min' => 'Total must be greater than or equal to 0',
            'remark.string' => 'Remark must be a string',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'sale_date',
            'qty',
            'price_per_liter',
            'total',
            'remark',
            'updated_by',
        ]);
    }
}
