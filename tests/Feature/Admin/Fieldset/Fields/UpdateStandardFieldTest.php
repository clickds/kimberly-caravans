<?php

namespace Tests\Feature\Admin\Fieldset\Fields;

use App\Models\Field;
use App\Models\Fieldset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateStandardFieldTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_label_is_required()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'label' => '',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('label');
    }

    public function test_input_name_is_required()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'input_name' => '',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('input_name');
    }

    public function test_required_is_required()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'required' => '',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('required');
    }

    public function test_required_is_a_boolean()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'required' => 'abc',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('required');
    }

    public function test_position_is_an_integer()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'position' => 'abc',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('position');
    }

    public function test_input_name_cannot_contain_spaces()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'input_name' => 'a b',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('input_name');
    }

    public function test_types_must_be_a_key_from_the_types_constant_in_fields()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'type' => 'ab',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('type');
    }

    public function test_input_name_can_be_the_same_as_that_on_another_form()
    {
        $field = $this->createField();
        $otherField = $this->createField();
        $data = $this->validFields([
            'input_name' => $otherField->input_name,
        ]);

        $response = $this->submit($field, $data);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $field->fieldset));
        $this->assertDatabaseHas('fields', $data);
    }

    public function test_successful()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'input_name' => $field->input_name,
        ]);

        $response = $this->submit($field, $data);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $field->fieldset));
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
            'width' => Field::WIDTH_FULL,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(Field $field, array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url($field);

        return $this->actingAs($user)->put($url, $data);
    }

    private function url(Field $field)
    {
        return route('admin.fieldsets.fields.update', [
            'fieldset' => $field->fieldset,
            'field' => $field,
        ]);
    }

    private function createField(array $attributes = [])
    {
        return factory(Field::class)->create($attributes);
    }

    private function createFieldset(array $attributes = [])
    {
        return factory(Fieldset::class)->create($attributes);
    }
}
