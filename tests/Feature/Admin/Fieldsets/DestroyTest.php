<?php

namespace Tests\Feature\Admin\Fieldsets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Fieldset;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $fieldset = $this->createFieldset();

        $response = $this->submit($fieldset);

        $response->assertRedirect(route('admin.fieldsets.index'));
        $this->assertDatabaseMissing('fieldsets', $fieldset->getAttributes());
    }

    private function submit(Fieldset $fieldset)
    {
        $user = $this->createSuperUser();
        $url = $this->url($fieldset);

        return $this->actingAs($user)->delete($url);
    }

    private function createFieldset($attributes = [])
    {
        return factory(Fieldset::class)->create($attributes);
    }

    private function url(Fieldset $fieldset)
    {
        return route('admin.fieldsets.destroy', [
            'fieldset' => $fieldset,
        ]);
    }
}
