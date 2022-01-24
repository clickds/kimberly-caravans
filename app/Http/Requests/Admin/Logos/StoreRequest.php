<?php

namespace App\Http\Requests\Admin\Logos;

use App\Models\Logo;
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
            'name' => [
                'required',
                'string',
            ],
            'external_url' => [
                'max:255',
                'nullable',
                'required_without:page_id',
                'string',
                'url',
            ],
            'page_id' => [
                'exists:pages,id',
                'nullable',
                'required_without:external_url',
            ],
            'display_location' => [
                'required',
                Rule::in(Logo::VALID_DISPLAY_LOCATIONS),
            ],
            'image' => [
                'required',
                'image',
            ],
        ];
    }
}
