<?php

namespace App\Http\Requests\Admin\Navigations\NavigationItems\Hierarchy;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'navigation_items' => [
                'required',
                'array',
            ],
            'navigation_items.*.navigationItemId' => [
                'required',
                'exists:navigation_items,id',
            ],
            'navigation_items.*.children' => [
                'sometimes',
                'array',
            ],
            'navigation_items.*.children.*' => [
                function ($attribute, $value, $fail) {
                    if (isset($value['children']) && [] !== $value['children']) {
                        $fail('Navigation items can only be nested one level.');
                    }
                },
            ],
            'navigation_items.*.children.*.navigationItemId' => [
                'required',
                'exists:navigation_items,id',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'navigation_items' => json_decode($this->navigation_items, true),
        ]);
    }
}
