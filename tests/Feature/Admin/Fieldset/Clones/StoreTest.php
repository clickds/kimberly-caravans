<?php

namespace Tests\Feature\Admin\Fieldset\Clones;

use App\Models\Field;
use App\Models\Fieldset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_clones_fieldset(): void
    {
        $oldFieldset = factory(Fieldset::class)->create();

        $response = $this->submit($oldFieldset);

        $response->assertRedirect();
        $latestFieldset = Fieldset::orderBy('id', 'desc')->first();

        $this->assertEquals(
            Arr::except($oldFieldset->getAttributes(), ['id', 'name', 'created_at', 'updated_at']),
            Arr::except($latestFieldset->getAttributes(), ['id', 'name', 'created_at', 'updated_at']),
        );

        $this->assertEquals(sprintf('%s Clone', $oldFieldset->name), $latestFieldset->name);
    }

    public function test_clones_fields(): void
    {
        $oldFieldset = factory(Fieldset::class)->create();
        $fields = factory(Field::class, 10)->create([
            'fieldset_id' => $oldFieldset->id,
        ]);

        $response = $this->submit($oldFieldset);

        $response->assertRedirect();
        $latestFieldset = Fieldset::latest()->first();
        foreach ($fields as $field) {
            $data = Arr::except($field->getAttributes(), ['id', 'created_at', 'updated_at']);
            $data['fieldset_id'] = $latestFieldset->id;
            $this->assertDatabaseHas('fields', $data);
        }
    }

    private function submit(Fieldset $fieldset): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.fieldsets.clones.store', $fieldset);

        return $this->actingAs($user)->post($url);
    }
}
