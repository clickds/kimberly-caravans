<?php

namespace App\Http\Requests\Admin\Videos;

use App\Models\Video;
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
            'title' => [
                'required',
            ],
            'type' => [
                'required',
                Rule::in(Video::VALID_TYPES),
            ],
            'video_category_ids.*' => [
                'required',
                'exists:video_categories,id',
            ],
            'embed_code' => [
                'required',
                'string',
            ],
            'excerpt' => [
                'required',
                'string',
            ],
            'published_at' => [
                'required',
                'date',
            ],
            'image' => [
                'image',
            ],
            'sites.*.id' => [
                'exists:sites,id',
            ],
            'dealer_id' => [
                'nullable',
                'exists:dealers,id',
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
