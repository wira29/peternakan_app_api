<?php

namespace App\Http\Requests\Vaccine;

use Illuminate\Foundation\Http\FormRequest;

use function Pest\Laravel\json;

class UpdateVaccineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view-vaccine-records');
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
            'name' => [
                'bail',
                'sometimes',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Vaccine name must be a string.',
            'name.max' => 'Vaccine name must not exceed 255 characters.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'name',
            'updated_by',
        ]);
    }
}
