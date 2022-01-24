<?php

namespace App\Http\Requests\Admin\Layouts;

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
            'code' => [
                'required',
                Rule::unique('layouts', 'code')->ignore($this->layoutId())
            ],
            'name' => [
                'required',
            ],
        ];
    }

    private function layoutId(): ?int
    {
        if ($layout = $this->route('layout')) {
            return $layout->id;
        }
        return null;
    }
}
