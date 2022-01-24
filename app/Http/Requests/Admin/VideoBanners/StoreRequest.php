<?php

namespace App\Http\Requests\Admin\VideoBanners;

use Illuminate\Foundation\Http\FormRequest;

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
            'mp4' => [
                'file',
                'mime_types:video/mp4',
            ],
            'name' => [
                'required',
                'unique:video_banners',
            ],
            'webm' => [
                'file',
                'mime_types:video/webm',
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
}
