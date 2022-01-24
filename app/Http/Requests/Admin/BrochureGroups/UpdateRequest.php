<?php

namespace App\Http\Requests\Admin\BrochureGroups;

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
                'string',
                Rule::unique('brochure_groups')->ignore($this->brochureGroupId())
            ],
            'position' => [
                'integer',
                'nullable',
            ],
        ];
    }

    private function brochureGroupId(): ?int
    {
        if ($brochureGroup = $this->route('brochure_group')) {
            return $brochureGroup->id;
        }
        return null;
    }
}
