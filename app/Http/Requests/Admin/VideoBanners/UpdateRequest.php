<?php

namespace App\Http\Requests\Admin\VideoBanners;

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
            'mp4' => [
                'file',
                'mime_types:video/mp4',
                'nullable',
            ],
            'name' => [
                'required',
                Rule::unique('video_banners')->ignore($this->videoBannerId()),
            ],
            'webm' => [
                'file',
                'mime_types:video/webm',
                'nullable',
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

    private function videoBannerId(): ?int
    {
        if ($videoBanner = $this->route('video_banner')) {
            return $videoBanner->id;
        }
        return null;
    }
}
