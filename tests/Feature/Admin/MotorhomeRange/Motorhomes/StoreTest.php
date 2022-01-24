<?php

namespace Tests\Feature\Admin\MotorhomeRange\Motorhomes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\MotorhomeSaved;
use App\Models\Berth;
use App\Models\MotorhomeRange;
use App\Models\Motorhome;
use App\Models\Layout;
use App\Models\Seat;
use App\Models\Site;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->fakeStorage();
        $motorhomeRange = $this->createRange();
        $berth = factory(Berth::class)->create();
        $seat = factory(Seat::class)->create();
        $data = $this->validFields([
            'day_floorplan' => UploadedFile::fake()->image('day_floorplan.jpg'),
            'night_floorplan' => UploadedFile::fake()->image('night_floorplan.jpg'),
            'berth_ids' => [$berth->id],
            'seat_ids' => [$seat->id],
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        $data = Arr::except($data, ['day_floorplan', 'night_floorplan', 'berth_ids', 'seat_ids']);
        $this->assertDatabaseHas('motorhomes', $data);
        $motorhome = $motorhomeRange->motorhomes()->first();
        $this->assertDatabaseHas('berth_motorhome', [
            'berth_id' => $berth->id,
            'motorhome_id' => $motorhome->id,
        ]);
        $this->assertDatabaseHas('motorhome_seat', [
            'seat_id' => $seat->id,
            'motorhome_id' => $motorhome->id,
        ]);
        $this->assertFileExists($motorhome->getFirstMedia('dayFloorplan')->getPath());
        $this->assertFileExists($motorhome->getFirstMedia('nightFloorplan')->getPath());
    }

    public function test_syncing_stock_item_images(): void
    {
        $motorhomeRange = $this->createRange();
        $stockImage = factory(Media::class)->create([
            'model_type' => MotorhomeRange::class,
            'model_id' => $motorhomeRange->id,
        ]);
        $data = $this->validFields([
            'stock_item_image_ids' => [$stockImage->id],
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        $this->assertDatabaseHas('motorhome_stock_item_images', [
            'media_id' => $stockImage->id,
        ]);
    }

    public function test_dispatches_events()
    {
        Event::fake();
        $motorhomeRange = $this->createRange();
        $data = $this->validFields();

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        Event::assertDispatched(MotorhomeSaved::class);
    }

    public function test_syncing_sites()
    {
        $motorhomeRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['id'] = $site->id;
        $data['sites'][0]['price'] = 500;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        $motorhome = Motorhome::first();
        $this->assertDatabaseHas('motorhome_site', [
            'site_id' => $site->id,
            'motorhome_id' => $motorhome->id,
            'price' => "500.00",
            'recommended_price' => "400.00",
        ]);
    }

    public function test_syncing_sites_sets_price_equal_to_recommended_price_if_price_is_null()
    {
        $motorhomeRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['id'] = $site->id;
        $data['sites'][0]['price'] = null;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        $motorhome = Motorhome::first();
        $this->assertDatabaseHas('motorhome_site', [
            'site_id' => $site->id,
            'motorhome_id' => $motorhome->id,
            'price' => "400.00",
            'recommended_price' => "400.00",
        ]);
    }

    public function test_does_sync_site_without_prices()
    {
        $motorhomeRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['id'] = $site->id;

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        $motorhome = Motorhome::first();
        $this->assertDatabaseHas('motorhome_site', [
            'site_id' => $site->id,
            'motorhome_id' => $motorhome->id,
            'price' => null,
            'recommended_price' => null,
        ]);
    }

    public function test_with_video(): void
    {
        $motorhomeRange = $this->createRange();
        $video = factory(Video::class)->create();
        $motorhomeRange->videos()->attach($video);
        $data = $this->validFields([
            'video_id' => $video->id,
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $motorhomeData = Arr::except($data, ['day_floorplan', 'night_floorplan']);
        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange));
        $this->assertDatabaseHas('motorhomes', $motorhomeData);
    }

    public function test_fails_validation_if_video_not_attached_to_range(): void
    {
        $motorhomeRange = $this->createRange();
        $video = factory(Video::class)->create();
        $data = $this->validFields([
            'video_id' => $video->id,
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('video_id');
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            $inputName => null,
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['layout_id'],
            ['day_floorplan'],
            ['name'],
            ['exclusive'],
            ['height_includes_aerial'],
            ['fuel'],
            ['transmission'],
            ['conversion'],
            ['engine_size'],
            ['chassis_manufacturer'],
            ['description'],
            ['year'],
            ['live'],
        ];
    }

    public function test_layout_id_exists()
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            'layout_id' => 140,
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('layout_id');
    }

    public function test_day_floorplan_is_an_image()
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            'day_floorplan' => 'abc',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('day_floorplan');
    }

    public function test_night_floorplan_is_an_image()
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            'night_floorplan' => 'abc',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('night_floorplan');
    }

    public function test_name_is_unique_based_on_year_and_motorhome_range_id()
    {
        $motorhomeRange = $this->createRange();
        $motorhome = $this->createMotorhome([
            'motorhome_range_id' => $motorhomeRange->id,
        ]);
        $data = $this->validFields([
            'name' => $motorhome->name,
            'year' => $motorhome->year,
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_fuel_is_in_motorhomes_fuels_array()
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            'fuel' => 'wrong',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('fuel');
    }

    public function test_transmission_is_in_motorhomes_transmissions_array()
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            'transmission' => 'wrong',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('transmission');
    }

    public function test_conversion_is_in_motorhomes_conversions_array()
    {
        $motorhomeRange = $this->createRange();
        $data = $this->validFields([
            'conversion' => 'wrong',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('conversion');
    }

    public function test_stock_item_images_must_belong_to_range(): void
    {
        $motorhomeRange = $this->createRange();
        $media = factory(Media::class)->state('caravan-range')->create();
        $data = $this->validFields([
            'stock_item_image_ids' => [$media->id],
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('stock_item_image_ids.0');
    }

    private function submit(MotorhomeRange $motorhomeRange, array $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhomeRange);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'exclusive' => true,
            'fuel' => Motorhome::FUEL_DIESEL,
            'transmission' => Motorhome::TRANSMISSION_AUTOMATIC,
            'conversion' => Motorhome::CONVERSION_A_CLASS,
            'chassis_manufacturer' => 'Renault',
            'engine_size' => '1000cc',
            'engine_power' => '1000cc',
            'position' => 0,
            'height_includes_aerial' => true,
            'height' => 5.24,
            'length' => 5.24,
            'width' => 5.24,
            'mro' => 250,
            'mtplm' => 350,
            'payload' => 100,
            'year' => 2019,
            'description' => 'Some description',
            'small_print' => 'Some small print',
            'day_floorplan' => UploadedFile::fake()->image('day_floorplan.jpg'),
            'night_floorplan' => UploadedFile::fake()->image('night_floorplan.jpg'),
            'live' => true,
        ];

        if (!array_key_exists('layout_id', $overrides)) {
            $defaults['layout_id'] = factory(Layout::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url(MotorhomeRange $motorhomeRange)
    {
        return route('admin.motorhome-ranges.motorhomes.store', $motorhomeRange);
    }

    private function createRange()
    {
        return factory(MotorhomeRange::class)->create();
    }

    private function createMotorhome($attributes = [])
    {
        return factory(Motorhome::class)->create($attributes);
    }
}
