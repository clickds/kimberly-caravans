<?php

namespace App\Services\FormBuilder\FieldValidationRules;

use Illuminate\Validation\Rule;

class SelectGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            Rule::in($this->getField()->options),
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
