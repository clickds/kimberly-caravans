<?php

namespace App\Http\Requests\Admin\VideoCategories;

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
                Rule::unique('video_categories')->ignore($this->videoCategoryId()),
            ],
            'position' => [
                'integer',
                'nullable',
            ],
        ];
    }

    private function videoCategoryId(): ?int
    {
        if ($videoCategory = $this->route('video_category')) {
            return $videoCategory->id;
        }
        return null;
    }
}
