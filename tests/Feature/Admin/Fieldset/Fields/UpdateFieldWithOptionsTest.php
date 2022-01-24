<?php

namespace Tests\Feature\Admin\Fieldset\Fields;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Fieldset;
use App\Models\Field;
use Illuminate\Support\Arr;

class UpdateFieldWithOptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_options_is_required()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'options' => '',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('options');
    }

    public function test_options_is_an_array()
    {
        $field = $this->createField();
        $data = $this->validFields([
            'options' => 'abc',
        ]);

        $response = $this->submit($field, $data);

        $response->assertSessionHasErrors('options');
    }


    public function test_successful()
    {
        $field = $this->createField();
        $data = $this->validFields();

        $response = $this->submit($field, $data);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $field->fieldset));

        $expectedData = Arr::except($data, 'options');
        $expectedData['options'] = json_encode($data['options']);
        $this->assertDatabaseHas('fields', $expectedData);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'input_name' => 'some_name',
            'label' => 'some label',
            'name' => 'some name',
            'position' => 0,
            'required' => true,
            'type' => Field::TYPES_REQUIRING_OPTIONS[array_rand(Field::TYPES_REQUIRING_OPTIONS)],
            'options' => [
                'Something',
            ],
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
}
