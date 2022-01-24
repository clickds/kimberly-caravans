<?php

namespace App\Http\Requests\Admin\Testimonials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'site_ids' => [
                'required',
                'array',
                Rule::exists('sites', 'id')
            ],
            'content' => [
                'required',
                'string',
            ],
            'name' => [
                'required',
                'string',
            ],
            'position' => [
                'integer',
            ],
            'published_at' => [
                'date',
                'nullable'
            ],

        ];
    }
}
