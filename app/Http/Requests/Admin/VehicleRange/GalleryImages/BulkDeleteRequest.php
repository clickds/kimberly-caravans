<?php

namespace App\Http\Requests\Admin\VehicleRange\GalleryImages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkDeleteRequest extends FormRequest
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
            'gallery_image_ids' => [
                'array',
                'required',
            ],
            'gallery_image_ids.*' => [
                Rule::exists('media', 'id')->where(
                    'collection_name',
                    $this->route('galleryType')
                ),
                'required',
            ],
        ];
    }
}
