<?php

namespace App\Http\Requests\Admin\Reviews;

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
            'caravan_range_ids' => [
                'array',
                'nullable',
            ],
            'caravan_range_ids.*' => [
                'exists:caravan_ranges,id',
            ],
            'date' => [
                'date',
                'required',
            ],
            'dealer_id' => [
                'exists:dealers,id',
                'nullable',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'image' => [
                'required',
                'image',
            ],
            'link' => [
                'required_without:review_file',
                'string',
                'nullable',
            ],
            'magazine' => [
                'required',
                'string',
            ],
            'motorhome_range_ids' => [
                'array',
                'nullable',
            ],
            'motorhome_range_ids.*' => [
                'exists:motorhome_ranges,id',
            ],
            'position' => [
                'integer',
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
            'review_file' => [
                'required_without:link',
                'nullable',
                'mimetypes:application/pdf',
                'max:10000'
            ],
            'site_ids.*' => [
                'exists:sites,id',
            ],
            'title' => [
                'required',
                'string',
            ],
            'text' => [
                'required',
                'string',
            ],
            'review_category_id' => [
                'required',
                Rule::exists('review_categories', 'id'),
            ],
        ];
    }
}
