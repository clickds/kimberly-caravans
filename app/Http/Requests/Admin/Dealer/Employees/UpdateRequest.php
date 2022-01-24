<?php

namespace App\Http\Requests\Admin\Dealer\Employees;

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
            'role' => [
                'required',
                'string',
            ],
            'phone' => [
                'string',
                'nullable',
            ],
            'email' => [
                'string',
                'nullable',
            ],
            'image' => [
                'image',
                'nullable',
            ],
            'position' => [
                'integer',
                'nullable',
            ]
        ];
    }
}
