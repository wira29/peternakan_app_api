<?php

namespace App\Http\Requests\MilkStock;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilkStockRequest extends FormRequest
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
            'qty' => (int) $this->qty,
            'created_by' => auth()->user()->id,
            'location_id' => auth()->user()->location_id,
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
            'location_id' => ['bail','required', 'exists:locations,id', 'unique:milk_stocks,location_id'],
            'qty' => ['bail','required', 'integer', 'min:0'],
            'created_by' => ['bail','required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'location_id.unique' => 'Lokasi sudah ada.',
            'location_id.required' => 'Lokasi harus diisi.',
            'location_id.exists' => 'Lokasi tidak ditemukan.',
            'qty.required' => 'Jumlah stok susu harus diisi.',
            'qty.integer' => 'Jumlah stok susu harus berupa angka.',
            'qty.min' => 'Jumlah stok susu minimal 0.',
            'created_by.required' => 'Pembuat harus diisi.',
            'created_by.exists' => 'Pembuat tidak ditemukan.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'location_id',
            'qty',
            'created_by',
        ]);
    }
}
