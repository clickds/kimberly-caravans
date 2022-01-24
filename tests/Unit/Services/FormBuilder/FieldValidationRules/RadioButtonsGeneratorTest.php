<?php

namespace Tests\Unit\Services\FormBuilder\FieldValidationRules;

use PHPUnit\Framework\TestCase;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator;
use App\Models\Field;
use Illuminate\Validation\Rules\In;

class RadioButtonsGeneratorTest extends TestCase
{
    public function test_default_validation_rules()
    {
        $options = ['abc', 'def', 'ghi'];
        $field = $this->buildField([
            'options' => $options,
        ]);
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();

        $this->assertArrayHasKey($field->input_name, $rules);
        $fieldRules = $rules[$field->input_name];
        $this->assertContains('nullable', $fieldRules);
        foreach ($fieldRules as $rule) {
            if (!is_string($rule)) {
                $this->assertInstanceOf(In::class, $rule);
                $ruleAsString = $rule->__toString();
                foreach ($options as $option) {
                    $this->assertStringContainsString($option, $ruleAsString);
                }
            }
        }
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
            'type' => Field::TYPE_RADIO_BUTTONS,
            'required' => false,
            'options' => ['abc', 'def', 'ghi'],
        ];
        $attributes = array_merge($defaults, $overrides);

        return new Field($attributes);
    }
}
