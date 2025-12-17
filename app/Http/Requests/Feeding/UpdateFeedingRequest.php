<?php

namespace App\Http\Requests\Feeding;


use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('feeding');
    }
    public function prepareForValidation() : void
    {
        \Log::info("Raw Content: " . $this->getContent());
        $data = $this->json()->all();
        \Log::info("Preparing data for validation: " . json_encode($data));
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
            'cage_id' => 'sometimes|exists:cages,id',
            'feed_location_id' => 'sometimes|exists:feed_locations,id',
            'qty' => 'sometimes|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'cage_id.exists' => 'The selected cage does not exist.',
            'feed_location_id.exists' => 'The selected feed location does not exist.',
            'qty.numeric' => 'Quantity must be a numeric value.',
        ];
    }

    public function getData(): array
    {
        return $this->only(['cage_id', 'feed_location_id', 'qty', 'updated_by']);
    }
}
