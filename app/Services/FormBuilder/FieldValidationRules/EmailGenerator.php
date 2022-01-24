<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class EmailGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            'email',
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
