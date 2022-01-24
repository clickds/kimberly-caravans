<?php

namespace App\Http\Requests\Admin\ReviewCategories;

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
                'string',
                Rule::unique('review_categories', 'name')->ignore($this->reviewCategoryId()),
            ],
        ];
    }

    private function reviewCategoryId(): ?int
    {
        if ($reviewCategory = $this->route('review_category')) {
            return $reviewCategory->id;
        }
        return null;
    }
}
