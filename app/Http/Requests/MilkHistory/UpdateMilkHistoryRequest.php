<?php

namespace App\Http\Requests\MilkHistory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMilkHistoryRequest extends FormRequest
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
            'goat_code' => ['bail','sometimes', 'exists:goats,code'],
            'milked_at' => ['bail','sometimes', 'date'],
            'qty' => ['bail','sometimes', 'integer', 'min:0'],
            'updated_by' => ['bail','required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'goat_code.exists' => 'Kode kambing tidak ditemukan.',
            'milked_at.date' => 'Tanggal pemerahan tidak valid.',
            'qty.integer' => 'Jumlah susu harus berupa angka.',
            'qty.min' => 'Jumlah susu minimal 0.',
            'updated_by.required' => 'Pengubah harus diisi.',
            'updated_by.exists' => 'Pengubah tidak ditemukan.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'goat_code',
            'milked_at',
            'qty',
            'updated_by',
        ]);
    }
}
