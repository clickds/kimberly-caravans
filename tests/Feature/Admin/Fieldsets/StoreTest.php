<?php

namespace Tests\Feature\Admin\Fieldsets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successful()
    {
        $data = $this->validFields();

        $response = $this->submit($data);
        $this->assertDatabaseHas('fieldsets', $data);
    }

    private function submit(array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($user)->post($url, $data);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'content' => 'abc',
        ];

        return array_merge($defaults, $overrides);
    }

    private function url()
    {
        return route('admin.fieldsets.store');
    }
}
