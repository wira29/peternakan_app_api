<?php

namespace App\Http\Requests\BlendTransaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlendTransactionRequest extends FormRequest
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
        \Log::info('Data Request: ' . json_encode(request()->all()));
        return [
            'feed_id' => 'bail|required|exists:feeds,id',
            'qty' => 'bail|required|numeric|min:0',
            'materials' => 'bail|required|array',
            'materials.*.material_id' => 'bail|required|exists:materials,id',
            'materials.*.qty' => 'bail|required|numeric|min:0',
            'date' => 'bail|required|date',
            'created_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'feed_id.required' => 'Feed ID is required.',
            'feed_id.exists' => 'The selected feed does not exist.',
            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a number.',
            'qty.min' => 'Quantity must be at least 0.',
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a valid date.',
            'created_by.required' => 'Creator ID is required.',
            'created_by.exists' => 'The selected creator does not exist.',
            'materials.required' => 'Materials are required.',
            'materials.array' => 'Materials must be an array.',
        ];
    }

    public function getData(): array
    {
        return $this->only(['feed_id','materials', 'qty', 'date', 'created_by']);
    }
}
