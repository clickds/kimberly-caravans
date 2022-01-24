<?php

namespace Tests\Feature\Admin\Fieldset\Fields;

use App\Models\Field;
use App\Models\Fieldset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreStandardFieldTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_name_is_required()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_label_is_required()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'label' => '',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('label');
    }

    public function test_input_name_is_required()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'input_name' => '',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('input_name');
    }

    public function test_required_is_required()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'required' => '',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('required');
    }

    public function test_required_is_a_boolean()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'required' => 'abc',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('required');
    }

    public function test_position_is_an_integer()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'position' => 'abc',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('position');
    }

    public function test_input_name_cannot_contain_spaces()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'input_name' => 'a b',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('input_name');
    }

    public function test_types_must_be_a_key_from_the_types_constant_in_fields()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'type' => 'ab',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('type');
    }

    public function test_input_name_can_be_the_same_as_that_on_another_form()
    {
        $fieldset = $this->createFieldset();
        $otherFieldset = $this->createFieldset();
        $field = factory(Field::class)->create([
            'fieldset_id' => $otherFieldset->id,
        ]);
        $data = $this->validFields([
            'input_name' => $field->input_name,
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $fieldset));
        $this->assertDatabaseHas('fields', $data);
    }

    public function test_successful()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields();

        $response = $this->submit($fieldset, $data);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $fieldset));
        $this->assertDatabaseHas('fields', $data);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'input_name' => 'some_name',
            'label' => 'some label',
            'name' => 'some name',
            'position' => 0,
            'required' => true,
            'type' => Field::TYPE_TEXT,
            'width' => $this->faker->randomElement(Field::WIDTHS),
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(Fieldset $fieldset, array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url($fieldset);

        return $this->actingAs($user)->post($url, $data);
    }

    private function url(Fieldset $fieldset)
    {
        return route('admin.fieldsets.fields.store', $fieldset);
    }

    private function createFieldset(array $attributes = [])
    {
        return factory(Fieldset::class)->create($attributes);
    }
}
