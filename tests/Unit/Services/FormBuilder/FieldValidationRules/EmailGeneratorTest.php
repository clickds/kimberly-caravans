<?php

namespace Tests\Unit\Services\FormBuilder\FieldValidationRules;

use PHPUnit\Framework\TestCase;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator;
use App\Models\Field;

class EmailGeneratorTest extends TestCase
{
    public function test_default_validation_rules()
    {
        $field = $this->buildField();
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();

        $this->assertArrayHasKey($field->input_name, $rules);
        $fieldRules = $rules[$field->input_name];
        $this->assertContains('email', $fieldRules);
        $this->assertContains('nullable', $fieldRules);
    }

    public function test_required_validation_rule()
    {
        $field = $this->buildField([
            'required' => true,
        ]);
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();

        $this->assertArrayHasKey($field->input_name, $rules);
        $fieldRules = $rules[$field->input_name];
        $this->assertContains('required', $fieldRules);
    }

    private function buildField(array $overrides = [])
    {
        $defaults = [
            'input_name' => 'some_input',
            'type' => Field::TYPE_EMAIL,
            'required' => false,
        ];
        $attributes = array_merge($defaults, $overrides);

        return new Field($attributes);
    }
}
