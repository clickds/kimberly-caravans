<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class TextAreaGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            'string',
        ];

        if ($this->isRequired()) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return [
            $this->inputName() => $rules,
        ];
    }
}
