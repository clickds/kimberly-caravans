<?php

namespace App\Services\FormBuilder\FieldValidationRules;

use Illuminate\Validation\Rule;

class RadioButtonsGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            Rule::in($this->options()),
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
