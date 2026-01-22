<?php

namespace App\Http\Requests\MilkSale;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilkSaleRequest extends FormRequest
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
            'location_id' => auth()->user()->location_id,
            'created_by' => auth()->user()->id
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
            'location_id' => ['bail','required', 'exists:locations,id'],
            'sale_date' => ['bail','required', 'date'],
            'qty' => ['bail','required', 'integer', 'min:0'],
            'price_per_liter' => ['bail','required', 'integer', 'min:0'],
            'remark' => ['bail','sometimes', 'string'],
            'created_by' => ['bail','required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'location_id.required' => 'Lokasi harus diisi.',
            'sale_date.required' => 'Tanggal penjualan harus diisi.',
            'sale_date.date' => 'Tanggal penjualan tidak valid.',
            'qty.required' => 'Jumlah susu yang dijual harus diisi.',
            'qty.integer' => 'Jumlah susu yang dijual harus berupa angka.',
            'qty.min' => 'Jumlah susu yang dijual minimal 0.',
            'price_per_liter.required' => 'Harga per liter harus diisi.',
            'price_per_liter.integer' => 'Harga per liter harus berupa angka.',
            'price_per_liter.min' => 'Harga per liter minimal 0.',
            'created_by.required' => 'Pembuat harus diisi.',
            'created_by.exists' => 'Pembuat tidak ditemukan.',
            'remark.string' => 'Keterangan harus berupa teks.',
            'remark.sometimes' => 'Keterangan bersifat opsional.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'location_id',
            'sale_date',
            'qty',
            'price_per_liter',
            'remark',
            'created_by',
        ]);
    }
}
