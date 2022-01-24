<?php

namespace App\Http\Requests\Admin\Pages\Clones;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Page;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'expired_at' => [
                'nullable',
                'date',
            ],
            'live' => [
                'required',
                'boolean',
            ],
            'name' => [
                'required',
                'max:100',
            ],
            'meta_title' => [
                'required',
                'string',
                'max:100',
            ],
            'meta_description' => [
                'nullable',
                'string',
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:pages,id'
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'site_id' => [
                'exists:sites,id',
                'required',
                'integer',
            ],
            'template' => [
                'required',
                Rule::in(Page::TEMPLATES),
            ],
            'variety' => [
                'required',
                Rule::in(Page::VARIETIES),
            ],
            'video_banner_id' => [
                'exists:video_banners,id',
                'nullable',
            ],
            'image_banner_ids' => [
                'array',
                'nullable',
            ],
            'image_banner_ids.*' => [
                'exists:image_banners,id',
            ],
        ];
    }
}
