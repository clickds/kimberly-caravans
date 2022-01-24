<?php

namespace App\Http\Requests\Admin\Manufacturers;

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
            'caravan_intro_text' => [
                'nullable',
                'string',
            ],
            'motorhome_intro_text' => [
                'nullable',
                'string',
            ],
            'caravan_image' => [
                'image',
                'nullable',
            ],
            'exclusive' => [
                'boolean',
                'required',
            ],
            'link_directly_to_stock_search' => [
                'boolean',
                'required',
            ],
            'motorhome_image' => [
                'image',
                'nullable',
            ],
            'name' => [
                'required',
                'unique:manufacturers',
            ],
            'logo' => [
                'required',
                'image',
                'nullable',
            ],
            'motorhome_position' => [
                'nullable',
                'numeric',
            ],
            'caravan_position' => [
                'nullable',
                'numeric',
            ],
            'sites' => [
                Rule::exists('sites', 'id')
            ],
        ];
    }
}
