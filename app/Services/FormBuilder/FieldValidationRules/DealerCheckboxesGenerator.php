<?php

namespace App\Services\FormBuilder\FieldValidationRules;

use App\Models\Dealer;
use Illuminate\Validation\Rule;

class DealerCheckboxesGenerator extends BaseGenerator
{
    public function call(): array
    {
        $arrayRulesKey = $this->inputName();
        $itemRulesKey  = $arrayRulesKey . ".*";

        return [
            $arrayRulesKey => $this->arrayRules(),
            $itemRulesKey => $this->itemRules(),
        ];
    }

    private function arrayRules(): array
    {
        $rules = [
            'array',
        ];

        if ($this->isRequired()) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return $rules;
    }

    private function itemRules(): array
    {
        return [
            Rule::in(Dealer::pluck('name')->toArray()),
        ];
    }
}
