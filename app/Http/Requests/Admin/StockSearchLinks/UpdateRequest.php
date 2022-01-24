<?php

namespace App\Http\Requests\Admin\StockSearchLinks;

use App\Models\StockSearchLink;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => [
                'required',
                'string',
            ],
            'type' => [
                'required',
                'string',
                Rule::in(StockSearchLink::TYPES),
            ],
            'image' => [
                'nullable',
                'image',
                Rule::dimensions()->minWidth(640),
            ],
            'mobile_image' => [
                'nullable',
                'image',
                Rule::dimensions()->minWidth(400),
            ],
            'site_id' => [
                'required',
                'exists:sites,id',
            ],
            'page_id' => [
                'required',
                'exists:pages,id',
            ],
        ];
    }
}
