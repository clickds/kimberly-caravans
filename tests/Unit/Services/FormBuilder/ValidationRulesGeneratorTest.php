<?php

namespace Tests\Unit\Services\FormBuilder;

use App\Models\Dealer;
use App\Models\Field;
use App\Models\Fieldset;
use App\Models\Form;
use App\Services\FormBuilder\ValidationRulesGenerator as Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationRulesGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider fieldProvider
     */
    public function test_form_validation_rules_generator($fieldAttributes)
    {
        $form = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $form->fieldsets()->attach($fieldset);
        $fieldAttributes['fieldset_id'] = $fieldset->id;
        $field = factory(Field::class)->create($fieldAttributes);
        if ($field->type == Field::TYPE_DEALER_SELECT) {
            factory(Dealer::class)->create();
        }

        $generator = new Generator($form);
        $rules = $generator->call();

        if (in_array($field->type, [Field::TYPE_CAPTCHA, Field::TYPE_SUBMIT])) {
            $this->assertArrayNotHasKey($field->input_name, $rules);
        } else {
            $this->assertArrayHasKey($field->input_name, $rules);
        }

        if (in_array($field->type, [Field::TYPE_MULTIPLE_CHECKBOXES])) {
            $key = "{$field->input_name}.*";
            $this->assertArrayHasKey($key, $rules);

            $arrayRules = $rules[$field->input_name];
            $this->assertContains('array', $arrayRules);
            $this->assertContains('required', $arrayRules);
        }
    }

    public function fieldProvider()
    {
        return [
            [
                [
                    'type' => Field::TYPE_CAPTCHA,
                    'required' => true,
                ],
            ],
            [
                [
                    'type' => Field::TYPE_CHECKBOX,
                    'required' => true,
                ],
            ],
            [
                [
                    'type' => Field::TYPE_EMAIL,
                    'required' => true,
                ],
            ],
            [
                [
                    'type' => Field::TYPE_MULTIPLE_CHECKBOXES,
                    'required' => true,
                    'options' => ['a', 'b', 'c'],
                ],
            ],
            [
                [
                    'type' => Field::TYPE_RADIO_BUTTONS,
                    'required' => true,
                    'options' => ['a', 'b', 'c'],
                ],
            ],
            [
                [
                    'type' => Field::TYPE_SELECT,
                    'required' => true,
                    'options' => ['a', 'b', 'c'],
                ],
            ],
            [
                [
                    'type' => Field::TYPE_TEXT,
                    'required' => true,
                ],
            ],
            [
                [
                    'type' => Field::TYPE_TEXTAREA,
                    'required' => true,
                ],
            ],
            [
                [
                    'type' => Field::TYPE_SUBMIT,
                ]
            ],
            [
                [
                    'type' => Field::TYPE_CAPTCHA,
                ]
            ],
            [
                [
                    'type' => Field::TYPE_DEALER_SELECT,
                ]
            ],
            [
                [
                    'type' => Field::TYPE_BUSINESS_AREA_SELECT,
                ]
            ],
            [
                [
                    'type' => Field::TYPE_FILE_UPLOAD,
                ]
            ],
        ];
    }
}
