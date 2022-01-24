<?php

namespace App\Http\Requests\Admin\UsefulLinks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'image' => [
                'image',
                'nullable',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'position' => [
                'integer',
                'required',
            ],
            'useful_link_category_id' => [
                'required',
                'exists:useful_link_categories,id',
            ],
            'url' => [
                'required',
                'string',
                'max:255',
                'url',
            ]
        ];
    }
}
