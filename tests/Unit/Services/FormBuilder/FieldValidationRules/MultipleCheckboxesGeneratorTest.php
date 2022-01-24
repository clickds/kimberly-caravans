<?php

namespace Tests\Unit\Services\FormBuilder\FieldValidationRules;

use PHPUnit\Framework\TestCase;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator;
use App\Models\Field;
use Illuminate\Validation\Rules\In;

class MultipleCheckboxesGeneratorTest extends TestCase
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
        $arrayRules = $rules[$field->input_name];
        $this->assertContains('array', $arrayRules);
        $this->assertContains('nullable', $arrayRules);

        $this->assertArrayHasKey("{$field->input_name}.*", $rules);
        $fieldRules = $rules["{$field->input_name}"];
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

    // As multiple values can be present the required validation will be put on the array validation
    public function test_required_validation_rule()
    {
        $field = $this->buildField([
            'required' => true,
        ]);
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();

        $this->assertArrayHasKey($field->input_name, $rules);
        $arrayRules = $rules[$field->input_name];
        $this->assertContains('array', $arrayRules);
        $this->assertContains('required', $arrayRules);
    }

    private function buildField(array $overrides = [])
    {
        $defaults = [
            'input_name' => 'some_input',
            'type' => Field::TYPE_MULTIPLE_CHECKBOXES,
            'required' => false,
            'options' => ['abc', 'def', 'ghi'],
        ];
        $attributes = array_merge($defaults, $overrides);

        return new Field($attributes);
    }
}
