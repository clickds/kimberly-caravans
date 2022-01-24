<?php

namespace Tests\Feature\Admin\Fieldset\Fields;

use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyFieldTest extends TestCase
{
    use RefreshDatabase;
    public function test_successful()
    {
        $field = $this->createField();
        $response = $this->submit($field);

        $response->assertRedirect(route('admin.fieldsets.fields.index', $field->fieldset));
        $this->assertDatabaseMissing('fields', $field->getAttributes());
    }

    private function submit(Field $field)
    {
        $user = $this->createSuperUser();
        $url = $this->url($field);

        return $this->actingAs($user)->delete($url);
    }

    private function url(Field $field)
    {
        return route('admin.fieldsets.fields.destroy', [
            'fieldset' => $field->fieldset,
            'field' => $field,
        ]);
    }

    private function createField(array $attributes = [])
    {
        return factory(Field::class)->create($attributes);
    }
}
