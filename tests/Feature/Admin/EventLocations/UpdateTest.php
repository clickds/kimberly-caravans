<?php

namespace Tests\Feature\Admin\EventLocations;

use App\Models\EventLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $eventLocation = factory(EventLocation::class)->create();

        $data = $this->validFields();

        $response = $this->submit($data, $eventLocation);

        $response->assertRedirect(route('admin.event-locations.index'));

        $this->assertDatabaseHas('event_locations', $data);
    }

    public function test_requires_a_name()
    {
        $eventLocation = factory(EventLocation::class)->create();

        $data = $this->validFields(['name' => '']);

        $response = $this->submit($data, $eventLocation);

        $response->assertSessionHasErrors('name');
    }

    public function test_requires_latitude()
    {
        $eventLocation = factory(EventLocation::class)->create();

        $data = $this->validFields(['latitude' => '']);

        $response = $this->submit($data, $eventLocation);

        $response->assertSessionHasErrors('latitude');
    }

    public function test_it_requires_longitude()
    {
        $eventLocation = factory(EventLocation::class)->create();

        $data = $this->validFields(['longitude' => '']);

        $response = $this->submit($data, $eventLocation);

        $response->assertSessionHasErrors('longitude');
    }

    private function submit(array $data, EventLocation $eventLocation): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($eventLocation);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'name' => 'some updated name',
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(EventLocation $eventLocation): string
    {
        return route('admin.event-locations.update', ['event_location' => $eventLocation]);
    }
}
