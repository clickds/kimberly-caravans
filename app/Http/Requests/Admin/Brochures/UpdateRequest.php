<?php

namespace App\Http\Requests\Admin\Brochures;

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
            'title' => [
                'required',
                'string',
            ],
            'brochure_file' => [
                'mimetypes:application/pdf',
                'max:10000'
            ],
            'image' => [
                'image',
            ],
            'url' => [
                'string',
                'nullable',
                'max:255',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
            'site_id' => [
                'required',
                'exists:sites,id',
            ],
            'group_id' => [
                'exists:brochure_groups,id',
            ],
            'motorhome_range.*.id' => [
                'nullable',
                'exists:motorhome_ranges, id'
            ],
            'caravan_range.*.id' => [
                'nullable',
                'exists:caravan_ranges, id'
            ],
        ];
    }
}
