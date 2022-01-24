<?php

namespace App\Http\Requests\Admin\ImageBanners;

use App\Models\ImageBanner;
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
            'content_background_colour' => [
                'string',
                'nullable',
                Rule::in($this->backgroundColours()),
            ],
            'content_text_colour' => [
                'required_with:content',
                'nullable',
                'string',
                Rule::in($this->textColours()),
            ],
            'content' => [
                'nullable',
                'string',
            ],
            'icon' => [
                'required',
                Rule::in(ImageBanner::ICONS),
            ],
            'image' => [
                'required',
                'image',
            ],
            'image_alt' => [
                'string',
                'nullable',
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'text_alignment' => [
                'required',
                Rule::in(array_keys(ImageBanner::TEXT_ALIGNMENTS)),
            ],
            'title_background_colour' => [
                'string',
                'nullable',
                Rule::in($this->backgroundColours()),
            ],
            'title_text_colour' => [
                'required',
                'string',
                Rule::in($this->textColours()),
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'live' => [
                'required',
                'boolean',
            ],
        ];
    }

    private function textColours(): array
    {
        return array_keys(ImageBanner::TEXT_COLOURS);
    }

    private function backgroundColours(): array
    {
        return array_keys(ImageBanner::BACKGROUND_COLOURS);
    }
}
