<?php

namespace App\Http\Requests\MilkHistory;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilkHistoryRequest extends FormRequest
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
            'goat_code' => ['bail','required', 'exists:goats,code'],
            'milked_at' => ['bail','required', 'date'],
            'qty' => ['bail','required', 'integer', 'min:0'],
            'created_by' => ['bail','required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'goat_code.required' => 'Kode kambing harus diisi.',
            'goat_code.exists' => 'Kode kambing tidak ditemukan.',
            'milked_at.required' => 'Tanggal pemerahan harus diisi.',
            'milked_at.date' => 'Tanggal pemerahan tidak valid.',
            'qty.required' => 'Jumlah susu harus diisi.',
            'qty.integer' => 'Jumlah susu harus berupa angka.',
            'qty.min' => 'Jumlah susu minimal 0.',
            'created_by.required' => 'Pembuat harus diisi.',
            'created_by.exists' => 'Pembuat tidak ditemukan.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'goat_code',
            'milked_at',
            'qty',
            'created_by',
        ]);
    }
}
