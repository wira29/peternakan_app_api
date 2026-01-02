<?php

namespace App\Http\Requests\SaleGoat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleGoatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('sale-goats');
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'updated_by' => auth()->user()->id,
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
            'goat_code' => 'bail|sometimes|exists:goats,code',
            'date' => 'bail|sometimes|date',
            'price' => 'bail|sometimes|integer|min:0',
            'remark' => 'bail|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'goat_code.exists' => 'Kode kambing tidak ditemukan.',
            'date.date' => 'Format tanggal tidak valid.',
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
            'updated_by',
        ]);
    }
}
