<?php

namespace App\Http\Requests\Goat;

use App\Enums\GoatOriginEnum;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\GoatGender;
use App\Enums\GoatOrigin;
use App\Enums\FemaleCondition;
use App\Enums\FemaleConditionEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use App\Models\Cage;

class StoreGoatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-goats');
    }

    public function prepareForValidation(): void
    {
        $user = auth()->user();
        $cage = $this->input('cage_id');
        $location_id = Cage::where('id', $cage)->value('location_id');
        $this->merge([
            'created_by' => $user->id,
            'location_id' => $location_id,
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
            'code' => 'bail|required|string|max:100|unique:goats,code',
            'breed_id' => 'bail|required|exists:breeds,id',
            'cage_id' => 'bail|required|exists:cages,id',
            'location_id'=> 'bail|required|exists:locations,id',
            'father_id' => 'bail|nullable|exists:goats,id',
            'mother_id' => 'bail|nullable|exists:goats,id',
            'origin' => [
                            'bail',
                            'required',
                            'string',
                            'max:255',
                            new Enum(GoatOriginEnum::class)
                        ],
            'color' => 'bail|nullable|string|max:100',
            'gender' => [
                            'bail',
                            'required',
                            new Enum(GoatGender::class) // <-- Gunakan sebagai objek
                        ],
            'date_of_birth' => 'bail|required|date_format:Y-m-d',
            'date_of_purchase' => 'bail|nullable|date_format:Y-m-d',
            'price' => 'bail|nullable|numeric|min:0',
            'female_condition' => [
                            'bail',
                            'nullable',
                            'string',
                            'max:255',
                            new Enum(FemaleConditionEnum::class)
                        ],
            'is_breeder' => 'bail|nullable|boolean',
            'is_qurbani' => 'bail|nullable|boolean',
            'remarks' => 'bail|nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Goat code is required.',
            'code.string' => 'Goat code must be a string.',
            'code.max' => 'Goat code must not exceed 100 characters.',
            'code.unique' => 'Goat code must be unique.',
            'breed_id.required' => 'Breed is required.',
            'breed_id.exists' => 'The selected breed does not exist.',
            'cage_id.required' => 'Cage is required.',
            'cage_id.exists' => 'The selected cage does not exist.',
            'father_id.exists' => 'The selected father goat does not exist.',
            'mother_id.exists' => 'The selected mother goat does not exist.',
            'origin.required' => 'Origin is required.',
            'origin.string' => 'Origin must be a string.',
            'origin.max' => 'Origin must not exceed 255 characters.',
            'color.string' => 'Color must be a string.',
            'color.max' => 'Color must not exceed 100 characters.',
            'gender.required' => 'Gender is required.',
            'gender.Enum' => 'Gender must be a valid value.',
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a valid date.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',
            'female_condition.Enum' => 'Female condition must be a valid value.',
            'is_breeder.required' => 'Is breeder field is required.',
            'is_breeder.boolean' => 'Is breeder must be true or false.',
            'is_qurbani.required' => 'Is qurbani field is required.',
            'is_qurbani.boolean' => 'Is qurbani must be true or false.',
            'remarks.string' => 'Remarks must be a string.',
            'remarks.max' => 'Remarks must not exceed 500 characters.',
        ];
    }
    public function getData(): array
    {
        return $this->only([
            'code',
            'breed_id',
            'cage_id',
            'location_id',
            'father_id',
            'mother_id',
            'origin',
            'color',
            'gender',
            'date',
            'price',
            'female_condition',
            'is_breeder',
            'is_qurbani',
            'remarks',
        ]);
    }
}