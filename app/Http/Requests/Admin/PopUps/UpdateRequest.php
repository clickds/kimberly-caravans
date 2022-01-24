<?php

namespace App\Http\Requests\Admin\PopUps;

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
            'appears_on_page_ids' => [
                'array',
                'nullable',
            ],
            'appears_on_page_ids.*' => [
                'exists:pages,id',
            ],
            'appears_on_all_pages' => [
                'boolean',
                'required',
            ],
            'appears_on_new_motorhome_pages' => [
                'boolean',
                'required',
            ],
            'appears_on_new_caravan_pages' => [
                'boolean',
                'required',
            ],
            'appears_on_used_motorhome_pages' => [
                'boolean',
                'required',
            ],
            'appears_on_used_caravan_pages' => [
                'boolean',
                'required',
            ],
            'caravan_range_ids' => [
                'array',
                'nullable',
            ],
            'caravan_range_ids.*' => [
                'exists:caravan_ranges,id',
            ],
            'desktop_image' => [
                'image',
                'nullable',
            ],
            'external_url' => [
                'required_without:page_id',
                'nullable',
                'url',
            ],
            'live' => [
                'boolean',
                'required',
            ],
            'mobile_image' => [
                'image',
                'nullable',
            ],
            'motorhome_range_ids' => [
                'array',
                'nullable',
            ],
            'motorhome_range_ids.*' => [
                'exists:motorhome_ranges,id',
            ],
            'name' => [
                'required',
            ],
            'page_id' => [
                'exists:pages,id',
                'required_without:external_url',
                'nullable',
            ],
            'site_id' => [
                'exists:sites,id',
                'required',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
        ];
    }
}
