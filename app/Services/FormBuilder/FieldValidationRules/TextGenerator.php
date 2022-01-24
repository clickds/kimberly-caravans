<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class TextGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            'max:255',
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
