<?php

namespace App\Http\Requests\Goat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\GoatGender;
use App\Enums\GoatOrigin;
use App\Enums\FemaleCondition;
use App\Enums\FemaleConditionEnum;
use App\Enums\GoatOriginEnum;

class UpdateGoatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-goats');
    }

    public function prepareForValidation() : void
    {
        $data = $this->json()->all();
        $data['updated_by'] = Auth::user()->id;
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
            'name' => 'bail|sometimes|string|max:255',
            'tag_number' => 'bail|sometimes|string|max:100|unique:goats,tag_number,' . $this->route('goat'),
            'breed_id' => 'bail|sometimes|exists:breeds,id',
            'cage_id' => 'bail|sometimes|exists:cages,id',
            'father_id' => 'bail|sometimes|nullable|exists:goats,id',
            'mother_id' => 'bail|sometimes|nullable|exists:goats,id',
            'origin' => 'bail|sometimes|string|max:255|enum:' . GoatOriginEnum::class,
            'color' => 'bail|sometimes|string|max:100',
            'gender' => 'bail|sometimes|enum:' . GoatGender::class,
            'date_of_birth' => 'bail|sometimes|date_format:Y-m-d',
            'date_of_purchase' => 'bail|sometimes|date_format:Y-m-d',
            'price' => 'bail|sometimes|nullable|numeric|min:0',
            'female_condition' => 'bail|sometimes|nullable|string|max:255|enum:' . FemaleConditionEnum::class,
            'is_breeder' => 'bail|sometimes|boolean',
            'is_qurbani' => 'bail|sometimes|boolean',
            'remarks' => 'bail|sometimes|string|max:500',
        ];
    }
    public function messages(): array
    {
        return [
            'name.string' => 'Goat name must be a string.',
            'name.max' => 'Goat name must not exceed 255 characters.',
            'tag_number.string' => 'Tag number must be a string.',
            'tag_number.max' => 'Tag number must not exceed 100 characters.',
            'tag_number.unique' => 'Tag number must be unique.',
            'breed_id.exists' => 'The selected breed does not exist.',
            'cage_id.exists' => 'The selected cage does not exist.',
            'father_id.exists' => 'The selected father goat does not exist.',
            'mother_id.exists' => 'The selected mother goat does not exist.',
            'origin.string' => 'Origin must be a string.',
            'origin.max' => 'Origin must not exceed 255 characters.',
            'color.string' => 'Color must be a string.',
            'color.max' => 'Color must not exceed 100 characters.',
            'gender.enum' => 'Gender must be a valid value.',
            'date_of_birth.date_format' => 'Date of birth must be in YYYY-MM-DD format.',
            'date_of_purchase.date_format' => 'Date of purchase must be in YYYY-MM-DD format.',           
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',
            'female_condition.string' => 'Female condition must be a string.',
            'female_condition.max' => 'Female condition must not exceed 255 characters.',
            'remarks.string' => 'Remarks must be a string.',
            'remarks.max' => 'Remarks must not exceed 500 characters.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'name',
            'tag_number',
            'breed_id',
            'cage_id',
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
            'updated_by',
        ]);
    }
}