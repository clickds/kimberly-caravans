<?php

namespace Tests\Unit\Services\FormBuilder\FieldValidationRules;

use Tests\TestCase;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator;
use App\Models\Dealer;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\In;

class DealerSelectGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_validation_rules()
    {
        $dealer = factory(Dealer::class)->create();
        $field = $this->buildField();
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();

        $this->assertArrayHasKey($field->input_name, $rules);
        $fieldRules = $rules[$field->input_name];
        $this->assertContains('nullable', $fieldRules);
        foreach ($fieldRules as $rule) {
            if (!is_string($rule)) {
                $this->assertInstanceOf(In::class, $rule);
                $ruleAsString = $rule->__toString();
                $this->assertStringContainsString($dealer->name, $ruleAsString);
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
            'type' => Field::TYPE_DEALER_SELECT,
            'required' => false,
        ];
        $attributes = array_merge($defaults, $overrides);

        return new Field($attributes);
    }
}
