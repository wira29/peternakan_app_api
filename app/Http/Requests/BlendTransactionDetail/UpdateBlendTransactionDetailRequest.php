<?php

namespace App\Http\Requests\BlendTransactionDetail;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlendTransactionDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-blend-materials');
    }

    public function prepareForValidation(): void
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
            'material_id' => 'bail|sometimes|nullable|exists:materials,id',
            'qty' => 'bail|sometimes|nullable|numeric|min:0',
            'updated_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'material_id.required' => 'Material ID is required.',
            'material_id.exists' => 'The selected material does not exist.',
            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a number.',
            'qty.min' => 'Quantity must be at least 0.',
            'updated_by.required' => 'Updater ID is required.',
            'updated_by.exists' => 'The selected updater does not exist.',
        ];
    }
    public function getData(): array
    {
        return $this->only(['material_id', 'qty', 'updated_by']);
    }
}
