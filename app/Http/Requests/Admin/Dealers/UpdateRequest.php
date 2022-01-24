<?php

namespace App\Http\Requests\Admin\Dealers;

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
            'feed_location_code' => [
                'nullable',
                'string',
            ],
            'site_id' => [
                'required',
                Rule::exists('sites', 'id'),
            ],
            'is_branch' => [
                'required',
                'boolean',
            ],
            'is_servicing_center' => [
                'required',
                'boolean',
            ],
            'can_view_motorhomes' => [
                'required',
                'boolean',
            ],
            'can_view_caravans' => [
                'required',
                'boolean',
            ],
            'video_embed_code' => [
                'nullable',
                'string',
            ],
            'facilities' => [
                'nullable',
                'string',
            ],
            'servicing_centre' => [
                'nullable',
                'string',
            ],
            'position' => [
                'required',
                'integer',
            ],
            'opening_hours' => [
                'nullable',
                'string',
            ],
            'latitude' => [
                'required',
                'numeric',
            ],
            'longitude' => [
                'required',
                'numeric',
            ],
            'line_1' => [
                'required',
                'string',
            ],
            'line_2' => [
                'nullable',
                'string',
            ],
            'town' => [
                'nullable',
                'string',
            ],
            'county' => [
                'nullable',
                'string',
            ],
            'postcode' => [
                'nullable',
                'string',
            ],
            'phone' => [
                'nullable',
                'string',
            ],
            'website_url' => [
                'nullable',
                'url',
            ],
            'website_link_text' => [
                'nullable',
                'string',
            ],
            'fax' => [
                'nullable',
                'string',
            ],
            'sat_nav_code' => [
                'nullable',
                'string',
            ],
            'google_maps_url' => [
                'required',
                'url',
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'expired_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
