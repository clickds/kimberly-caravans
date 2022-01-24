<?php

namespace App\Http\Requests\Admin\ArticleCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('article_categories')->ignore($this->articleCategoryId()),
            ],
        ];
    }

    private function articleCategoryId(): ?int
    {
        if ($articleCategory = $this->route('article_category')) {
            return $articleCategory->id;
        }
        return null;
    }
}
