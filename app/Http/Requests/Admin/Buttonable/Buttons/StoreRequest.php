<?php

namespace App\Http\Requests\Admin\Buttonable\Buttons;

use App\Models\Button;
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
            'colour' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_keys(Button::COLOURS)),
            ],
            'external_url' => [
                'max:255',
                'nullable',
                'required_without:link_page_id',
                'string',
                'url',
            ],
            'link_page_id' => [
                'exists:pages,id',
                'nullable',
                'required_without:external_url',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'position' => [
                'integer',
                'nullable',
            ],
        ];
    }
}
