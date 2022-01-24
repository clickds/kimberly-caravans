<?php

namespace App\Http\Requests\Admin\EventLocations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'address_line_1' => [
                'nullable',
                'string',
            ],
            'address_line_2' => [
                'nullable',
                'string',
            ],
            'town' => [
                'nullable',
                'string',
            ],
            'county' => [
                'nullable',
                'string',
            ],
            'postcode' => [
                'nullable',
                'string',
            ],
            'phone' => [
                'nullable',
                'string',
            ],
            'fax' => [
                'nullable',
                'string',
            ],
            'latitude' => [
                'required',
            ],
            'longitude' => [
                'required'
            ],
        ];
    }
}
