<?php

namespace Tests\Feature\Admin\Fieldset\Fields;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Fieldset;
use App\Models\Field;
use Illuminate\Support\Arr;

class StoreFieldWithOptionsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_options_is_required()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'options' => '',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('options');
    }

    public function test_options_is_an_array()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'options' => 'abc',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('options');
    }


    public function test_successful()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields();

        $response = $this->submit($fieldset, $data);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $fieldset));

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
