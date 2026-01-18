<?php

namespace App\Http\Requests\MilkStock;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMilkStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-milk');
    }

    public function prepareForValidation(): void
    {
        $this->merge([
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
            'location_id' => ['bail','sometimes', 'exists:locations,id'],
            'qty' => ['bail','sometimes', 'integer', 'min:0'],
            'updated_by' => ['bail','required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'location_id.exists' => 'Lokasi tidak ditemukan.',
            'qty.integer' => 'Jumlah stok susu harus berupa angka.',
            'qty.min' => 'Jumlah stok susu minimal 0.',
            'updated_by.required' => 'Pengubah harus diisi.',
            'updated_by.exists' => 'Pengubah tidak ditemukan.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'location_id',
            'qty',
            'updated_by',
        ]);
    }
}
