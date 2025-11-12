<?php

namespace App\Http\Requests\FeedSale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('sale-feeds');
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
            'location_id' => 'bail|sometimes|exists:locations,id',
            'sale_date' => 'bail|sometimes|date',
            'feeds' => 'bail|sometimes|array',
            'updated_by' => 'bail|required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'location_id.exists' => 'The selected location does not exist.',
            'sale_date.date' => 'Sale date must be a valid date.',
            'feeds.array' => 'Feed data must be an array.',
            'updated_by.required' => 'Updater ID is required.',
            'updated_by.exists' => 'The selected updater does not exist.',
        ];
    }

    public function getData(): array
    {
        return $this->only([
            'location_id',
            'sale_date',
            'feeds',
            'updated_by',
        ]);
    }
}
