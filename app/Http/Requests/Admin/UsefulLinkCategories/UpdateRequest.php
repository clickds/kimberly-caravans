<?php

namespace App\Http\Requests\Admin\UsefulLinkCategories;

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
                Rule::unique('useful_link_categories')->ignore($this->usefulLinkCategoryId()),
            ],
            'position' => [
                'integer',
                'nullable',
            ],
        ];
    }

    private function usefulLinkCategoryId(): ?int
    {
        $usefulLinkCategory = $this->route('usefulLinkCategory');
        if ($usefulLinkCategory) {
            return $usefulLinkCategory->id;
        }
        return null;
    }
}
