<?php

namespace Tests\Feature\Admin\Fieldsets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Fieldset;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($fieldset, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successful()
    {
        $fieldset = $this->createFieldset();
        $data = $this->validFields();

        $response = $this->submit($fieldset, $data);

        $response->assertRedirect(route('admin.fieldsets.index'));
        $this->assertDatabaseHas('fieldsets', $data);
    }

    private function submit(Fieldset $fieldset, array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url($fieldset);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'content' => 'abc',
        ];

        return array_merge($defaults, $overrides);
    }

    private function createFieldset($attributes = [])
    {
        return factory(Fieldset::class)->create($attributes);
    }

    private function url(Fieldset $fieldset)
    {
        return route('admin.fieldsets.update', [
            'fieldset' => $fieldset,
        ]);
    }
}
