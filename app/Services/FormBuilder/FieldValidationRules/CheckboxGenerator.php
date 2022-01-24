<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class CheckboxGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            'boolean',
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
