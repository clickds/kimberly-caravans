<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class NoValidationGenerator extends BaseGenerator
{
    public function call(): array
    {
        return [];
    }
}
