<?php

namespace App\Services\FormBuilder\FieldValidationRules;

use App\Models\BusinessArea;
use Illuminate\Validation\Rule;

class BusinessAreaSelectGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            Rule::in($this->businessAreaNames()),
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

    private function businessAreaNames(): array
    {
        return BusinessArea::pluck('name')->toArray();
    }
}
