<?php

namespace App\Services\FormBuilder\FieldValidationRules;

class CaptchaGenerator extends BaseGenerator
{
    public function call(): array
    {
        return [
            recaptchaFieldName() => [
                recaptchaRuleName(),
            ]
        ];
    }
}
