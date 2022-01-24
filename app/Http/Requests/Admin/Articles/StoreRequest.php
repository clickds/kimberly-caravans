<?php

namespace App\Http\Requests\Admin\Articles;

use App\Models\Article;
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
            'article_category_ids' => [
                'array',
                'required',
            ],
            'article_category_ids.*' => [
                'exists:article_categories,id',
                'required',
            ],
            'caravan_range_ids' => [
                'array',
                'nullable',
            ],
            'caravan_range_ids.*' => [
                'exists:caravan_ranges,id',
                'required',
            ],
            'date' => [
                'date',
                'required',
            ],
            'dealer_id' => [
                'exists:dealers,id',
                'nullable',
            ],
            'excerpt' => [
                'required',
                'string',
            ],
            'motorhome_range_ids' => [
                'array',
                'nullable',
            ],
            'motorhome_range_ids.*' => [
                'exists:motorhome_ranges,id',
                'required',
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
            'style' => [
                'required',
                Rule::in(Article::STYLES),
            ],
            'title' => [
                'required',
            ],
            'type' => [
                'required',
                Rule::in(Article::TYPES),
            ],
            'image' => [
                'required',
                'image',
            ],
            'site_ids.*' => [
                Rule::exists('sites', 'id')
            ],
            'expired_at' => [
                'nullable',
                'date',
            ],
            'live' => [
                'required',
                'boolean',
            ],
        ];
    }
}
