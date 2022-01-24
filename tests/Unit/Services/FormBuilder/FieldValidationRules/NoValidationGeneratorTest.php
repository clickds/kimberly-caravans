<?php

namespace Tests\Unit\Services\FormBuilder\FieldValidationRules;

use PHPUnit\Framework\TestCase;
use App\Services\FormBuilder\FieldValidationRules\BaseGenerator;
use App\Models\Field;

class NoValidationGeneratorTest extends TestCase
{
    public function test_submit_field()
    {
        $field = $this->buildField([
            'type' => Field::TYPE_SUBMIT,
        ]);
        $generator = BaseGenerator::for($field);

        $rules = $generator->call();

        $this->assertEmpty($rules);
    }

    private function buildField(array $overrides = [])
    {
        $defaults = [
            'input_name' => 'some_input',
            'type' => Field::TYPE_TEXT,
            'required' => false,
        ];
        $attributes = array_merge($defaults, $overrides);

        return new Field($attributes);
    }
}
