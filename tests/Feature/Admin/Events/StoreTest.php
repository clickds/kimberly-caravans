<?php

namespace Tests\Feature\Admin\Events;

use App\Models\Dealer;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Tests\TestCase;
use App\Models\Event;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeStorage();

        $this->createDefaultSite();

        $this->createDealer();
    }

    public function test_successful()
    {
        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.events.index'));

        $event = Event::orderBy('id', 'desc')->first();

        $this->assertFileExists($event->getFirstMedia('image')->getPath());

        $this->assertDatabaseHas('events', Arr::except($data, ['image']));
    }

    public function test_creates_page_for_default_site()
    {
        $defaultSiteId = Site::where('is_default', true)->firstOrFail()->id;

        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.events.index'));

        $event = Event::orderBy('id', 'desc')->first();

        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Event::class,
            'pageable_id' => $event->id,
            'site_id' => $defaultSiteId,
        ]);

        $this->assertDatabaseHas('pages', [
            'site_id' => $defaultSiteId,
            'pageable_type' => Event::class,
            'pageable_id' => $event->id,
        ]);
    }

    public function test_it_requires_a_name()
    {
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_requires_an_event_location_id_without_a_dealer_id()
    {
        $data = $this->validFields(['dealer_id' => null]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('event_location_id');
    }

    public function test_it_requires_a_summary()
    {
        $data = $this->validFields(['summary' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('summary');
    }


    public function test_it_requires_a_start_date()
    {
        $data = $this->validFields(['start_date' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('start_date');
    }

    public function test_it_requires_start_date_to_be_a_date()
    {
        $data = $this->validFields(['start_date' => 'abc']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('start_date');
    }

    public function test_it_requires_a_end_date()
    {
        $data = $this->validFields(['end_date' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('end_date');
    }

    public function test_it_requires_end_date_to_be_a_date()
    {
        $data = $this->validFields(['end_date' => 'abc']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('end_date');
    }

    public function test_requires_an_image()
    {
        $data = $this->validFields(['image' => null]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('image');
    }

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function createDealer(): void
    {
        factory(Dealer::class)->create();
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'summary' => 'some summary',
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'image' => UploadedFile::fake()->image('image.jpg'),
            'dealer_id' => Dealer::firstOrFail()->id,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url()
    {
        return route('admin.events.store');
    }
}
