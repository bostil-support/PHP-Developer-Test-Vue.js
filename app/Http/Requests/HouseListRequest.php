<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'price_from' => 'nullable|integer',
            'price_to' => 'nullable|integer',
            'bedrooms' => 'nullable|array',
            'bedrooms.*' => 'integer',
            'bathrooms' => 'nullable|array',
            'bathrooms.*' => 'integer',
            'storeys' => 'nullable|array',
            'storeys.*' => 'integer',
            'garages' => 'nullable|array',
            'garages.*' => 'integer',
        ];
    }
}
