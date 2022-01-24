<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class FileUploadGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            'file',
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
