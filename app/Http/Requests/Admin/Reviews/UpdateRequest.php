<?php

namespace App\Http\Requests\Admin\Reviews;

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
        $rules = [
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
                'image',
                'nullable',
            ],
            'link' => [
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

        if ($this->reviewMissingReviewFile()) {
            $rules['link'][] = 'required_without:review_file';
            $rules['review_file'][] = 'required_without:link';
        }

        return $rules;
    }

    private function reviewMissingReviewFile(): bool
    {
        $review = $this->route('review');
        if (is_null($review)) {
            return true;
        }

        if ($review->hasMedia('review_file')) {
            return false;
        }
        return true;
    }
}
