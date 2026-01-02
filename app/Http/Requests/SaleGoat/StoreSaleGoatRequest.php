<?php

namespace App\Http\Requests\SaleGoat;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleGoatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('sale-goats');
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'created_by' => auth()->user()->id,
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
            'goat_code' => ['required', 'exists:goats,code'],
            'date' => ['required', 'date'],
            'price' => ['required', 'integer', 'min:0'],
            'remark' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'goat_code.required' => 'Kode kambing wajib diisi.',
            'goat_code.exists' => 'Kode kambing tidak ditemukan.',
            'date.required' => 'Tanggal penjualan wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'price.required' => 'Harga penjualan wajib diisi.',
            'price.integer' => 'Harga penjualan harus berupa angka.',
            'price.min' => 'Harga penjualan tidak boleh kurang dari 0.',
            'remark.string' => 'Keterangan harus berupa teks.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'goat_code',
            'date',
            'price',
            'remark',
            'created_by',
        ]);
    }
}
