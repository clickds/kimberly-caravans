<?php

namespace Tests\Feature\Admin\Events;

use App\Models\Dealer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Event;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $this->createDefaultSite();

        $event = $this->createEvent();

        $data = $this->validFields();

        $response = $this->submit($event, $data);

        $response->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('events', $data);
    }

    public function test_creates_page_for_default_site()
    {
        $defaultSite = $this->createDefaultSite();

        $event = $this->createEvent();

        $data = $this->validFields();

        $response = $this->submit($event, $data);

        $response->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Event::class,
            'pageable_id' => $event->id,
            'site_id' => $defaultSite->id,
        ]);

        $this->assertDatabaseHas('pages', [
            'site_id' => $defaultSite->id,
            'pageable_type' => Event::class,
            'pageable_id' => $event->id,
        ]);
    }

    public function test_requires_an_event_location_id_without_a_dealer_id()
    {
        $event = $this->createEvent();

        $data = $this->validFields(['dealer_id' => null]);

        $response = $this->submit($event, $data);

        $response->assertSessionHasErrors('event_location_id');
    }

    public function test_it_requires_a_name()
    {
        $event = $this->createEvent();

        $data = $this->validFields(['name' => '']);

        $response = $this->submit($event, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_requires_a_start_date()
    {
        $event = $this->createEvent();
        $data = $this->validFields(['start_date' => '']);

        $response = $this->submit($event, $data);

        $response->assertSessionHasErrors('start_date');
    }

    public function test_it_requires_start_date_to_be_a_date()
    {
        $event = $this->createEvent();
        $data = $this->validFields(['start_date' => 'abc']);

        $response = $this->submit($event, $data);

        $response->assertSessionHasErrors('start_date');
    }

    public function test_it_requires_a_end_date()
    {
        $event = $this->createEvent();
        $data = $this->validFields(['end_date' => '']);

        $response = $this->submit($event, $data);

        $response->assertSessionHasErrors('end_date');
    }

    public function test_it_requires_end_date_to_be_a_date()
    {
        $event = $this->createEvent();
        $data = $this->validFields(['end_date' => 'abc']);

        $response = $this->submit($event, $data);

        $response->assertSessionHasErrors('end_date');
    }

    private function submit(Event $event, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url($event);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'summary' => 'some excerpt',
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'dealer_id' => Dealer::firstOrFail()->id,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Event $event): string
    {
        return route('admin.events.update', $event);
    }

    private function createEvent(): Event
    {
        $dealer = factory(Dealer::class)->create();

        $event = factory(Event::class)->create();

        $event->dealer()->associate($dealer);

        return $event;
    }
}
