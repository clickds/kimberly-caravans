<?php

namespace Tests\Feature\Admin\EventLocations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.event-locations.index'));

        $this->assertDatabaseHas('event_locations', $data);
    }

    public function test_requires_a_name()
    {
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_requires_latitude()
    {
        $data = $this->validFields(['latitude' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('latitude');
    }

    public function test_it_requires_longitude()
    {
        $data = $this->validFields(['longitude' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('longitude');
    }

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();

        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(): string
    {
        return route('admin.event-locations.store');
    }
}
