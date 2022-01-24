<?php

namespace Tests\Feature\Admin\EventLocations;

use App\Models\EventLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $eventLocation = factory(EventLocation::class)->create();

        $response = $this->submit($eventLocation);

        $response->assertRedirect(route('admin.event-locations.index'));

        $this->assertDatabaseMissing('event_locations', ['id' => $eventLocation->id]);
    }

    private function submit(EventLocation $eventLocation): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($eventLocation);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(EventLocation $eventLocation): string
    {
        return route('admin.event-locations.destroy', ['event_location' => $eventLocation]);
    }
}
