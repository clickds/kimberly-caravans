<?php

namespace App\Http\Requests\Admin\CaravanRange\RangeFeatures;

use Illuminate\Foundation\Http\FormRequest;

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
            'content' => [
                'required',
                'string',
            ],
            'key' => [
                'boolean',
                'required',
            ],
            'name' => [
                'required',
                'string',
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'site_ids.*' => [
                'exists:sites,id',
            ],
            'warranty' => [
                'boolean',
                'required',
            ],
        ];
    }
}
