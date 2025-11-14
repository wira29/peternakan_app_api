<?php

namespace App\Http\Requests\MaterialTransaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-orders-materials');
    }

    public function prepareForValidation() : void
    {
        $data = $this->json()->all();
        $data['created_by'] = auth()->user()->id;
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
            'supplier' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.qty' => 'required|integer|min:1',
            'materials.*.price' => 'required|numeric|min:0',
            
        ];
    }
    public function messages(): array
    {
        return [
            'supplier.required' => 'Supplier is required.',
            'supplier.string' => 'Supplier must be a string.',
            'supplier.max' => 'Supplier must not exceed 255 characters.',
            'transaction_date.required' => 'Transaction date is required.',
            'transaction_date.date' => 'Transaction date must be a valid date.',
            'materials.required' => 'Materials are required.',
            'materials.array' => 'Materials must be an array.',
            'materials.*.material_id.required' => 'Material ID is required for each material.',
            'materials.*.material_id.exists' => 'Material ID must exist in the materials table.',
            'materials.*.qty.required' => 'Quantity is required for each material.',
            'materials.*.qty.integer' => 'Quantity must be an integer.',
            'materials.*.qty.min' => 'Quantity must be at least 1.',
            'materials.*.price.required' => 'Price is required for each material.',
            'materials.*.price.numeric' => 'Price must be a numeric value.',
            'materials.*.price.min' => 'Price must be at least 0.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'supplier',
            'total',
            'transaction_date',
            'materials',
            'created_by',
        ]);
    }
}
