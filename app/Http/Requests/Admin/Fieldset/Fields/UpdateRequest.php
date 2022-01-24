<?php

namespace App\Http\Requests\Admin\Fieldset\Fields;

use App\Models\Field;
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
        $rules = [
            'crm_field_name' => [
                Rule::in(Field::CRM_FIELD_NAMES),
                'nullable',
            ],
            'input_name' => [
                'alpha_dash',
                'required',
                'string',
            ],
            'label' => [
                'required',
                'string',
            ],
            'name' => [
                'required',
                'string',
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'required' => [
                'boolean',
                'required',
            ],
            'type' => [
                'required',
                Rule::in(array_keys(Field::TYPES)),
            ],
            'width' => [
                'required',
                Rule::in(Field::WIDTHS),
            ],
        ];

        if (Field::typeRequiresOptions($this->get('type', ''))) {
            $rules['options'] = [
                'required',
                'array',
            ];
            $rules['options.*'] = [
                'required',
                'string',
            ];
        }

        return $rules;
    }
}
