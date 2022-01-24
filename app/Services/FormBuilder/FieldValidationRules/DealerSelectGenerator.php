<?php

namespace App\Services\FormBuilder\FieldValidationRules;

use App\Models\Dealer;
use Illuminate\Validation\Rule;

class DealerSelectGenerator extends BaseGenerator
{
    public function call(): array
    {
        $rules = [
            Rule::in($this->dealerNames()),
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

    private function dealerNames(): array
    {
        return Dealer::pluck('name')->toArray();
    }
}
