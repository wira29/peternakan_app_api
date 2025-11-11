<?php

namespace App\Http\Requests\BlendTransactionDetail;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlendTransactionDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-blend-materials');
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
            'blend_transaction_id' => 'bail|required|exists:blend_transactions,id',
            'material_id' => 'bail|required|exists:materials,id',
            'qty' => 'bail|required|numeric|min:0',
            'created_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'blend_transaction_id.required' => 'Blend transaction ID is required.',
            'blend_transaction_id.exists' => 'The selected blend transaction does not exist.',
            'material_id.required' => 'Material ID is required.',
            'material_id.exists' => 'The selected material does not exist.',
            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a number.',
            'qty.min' => 'Quantity must be at least 0.',
            'created_by.required' => 'Creator ID is required.',
            'created_by.exists' => 'The selected creator does not exist.',
        ];
    }

    public function getData(): array
    {
        return $this->only(['blend_transaction_id', 'material_id', 'qty', 'created_by']);
    }
}
