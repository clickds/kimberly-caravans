<?php

namespace Tests\Unit\Services\FormBuilder\FieldValidationRules;

use PHPUnit\Framework\TestCase;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator;
use App\Models\Field;

class CaptchaGeneratorTest extends TestCase
{
    public function test_default_validation_rules()
    {
        $field = $this->buildField();
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();
        $this->assertArrayHasKey(recaptchaFieldName(), $rules);

        $fieldRules = $rules[recaptchaFieldName()];
        $this->assertContains(recaptchaRuleName(), $fieldRules);
    }

    private function buildField(array $overrides = []): Field
    {
        $defaults = [
            'input_name' => 'captcha_input',
            'type' => Field::TYPE_CAPTCHA,
            'required' => false,
        ];
        $attributes = array_merge($defaults, $overrides);

        return new Field($attributes);
    }
}
