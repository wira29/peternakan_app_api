<?php

namespace App\Http\Requests\BlendTransaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlendTransactionRequest extends FormRequest
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
            'feed_id' => 'bail|sometimes|exists:feeds,id',
            'qty' => 'bail|sometimes|numeric|min:0',
            'date' => 'bail|sometimes|date',
            'updated_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'feed_id.exists' => 'The selected feed does not exist.',
            'qty.numeric' => 'Quantity must be a number.',
            'qty.min' => 'Quantity must be at least 0.',
            'date.date' => 'Date must be a valid date.',
            'updated_by.required' => 'Updater ID is required.',
            'updated_by.exists' => 'The selected updater does not exist.',
        ];
    }
    public function getData(): array
    {
        return $this->only(['feed_id', 'qty', 'date', 'updated_by']);
    }
}
