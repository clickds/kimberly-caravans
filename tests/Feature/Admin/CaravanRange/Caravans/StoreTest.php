<?php

namespace Tests\Feature\Admin\CaravanRange\Caravans;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\CaravanSaved;
use App\Models\Berth;
use App\Models\CaravanRange;
use App\Models\Caravan;
use App\Models\Layout;
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
        $caravanRange = $this->createRange();
        $berth = factory(Berth::class)->create();
        $data = $this->validFields([
            'day_floorplan' => UploadedFile::fake()->image('day_floorplan.jpg'),
            'night_floorplan' => UploadedFile::fake()->image('night_floorplan.jpg'),
            'berth_ids' => [$berth->id],
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $data = Arr::except($data, ['day_floorplan', 'night_floorplan', 'berth_ids']);
        $this->assertDatabaseHas('caravans', $data);
        $caravan = $caravanRange->caravans()->first();
        $this->assertDatabaseHas('berth_caravan', [
            'berth_id' => $berth->id,
            'caravan_id' => $caravan->id,
        ]);
        $this->assertFileExists($caravan->getFirstMedia('dayFloorplan')->getPath());
        $this->assertFileExists($caravan->getFirstMedia('nightFloorplan')->getPath());
    }

    public function test_syncing_stock_item_images(): void
    {
        $caravanRange = $this->createRange();
        $stockImage = factory(Media::class)->create([
            'model_type' => CaravanRange::class,
            'model_id' => $caravanRange->id,
        ]);
        $data = $this->validFields([
            'stock_item_image_ids' => [$stockImage->id],
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $this->assertDatabaseHas('caravan_stock_item_images', [
            'media_id' => $stockImage->id,
        ]);
    }

    public function test_syncing_sites()
    {
        $caravanRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['id'] = $site->id;
        $data['sites'][0]['price'] = 500;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $caravan = Caravan::first();
        $this->assertDatabaseHas('caravan_site', [
            'caravan_id' => $caravan->id,
            'price' => "500.00",
            'recommended_price' => "400.00",
        ]);
    }

    public function test_syncing_sites_sets_price_equal_to_recommended_price_if_price_is_null()
    {
        $caravanRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['id'] = $site->id;
        $data['sites'][0]['price'] = null;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $caravan = Caravan::first();
        $this->assertDatabaseHas('caravan_site', [
            'caravan_id' => $caravan->id,
            'price' => "400.00",
            'recommended_price' => "400.00",
        ]);
    }

    public function test_does_not_sync_site_without_id(): void
    {
        $caravanRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['price'] = 500;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $caravan = Caravan::first();
        $this->assertDatabaseMissing('caravan_site', [
            'caravan_id' => $caravan->id,
            'price' => "500.00",
            'recommended_price' => "400.00",
        ]);
    }

    public function test_with_video(): void
    {
        $caravanRange = $this->createRange();
        $video = factory(Video::class)->create();
        $caravanRange->videos()->attach($video);
        $data = $this->validFields([
            'video_id' => $video->id,
        ]);

        $response = $this->submit($caravanRange, $data);

        $caravanData = Arr::except($data, ['day_floorplan', 'night_floorplan']);
        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $this->assertDatabaseHas('caravans', $caravanData);
    }

    public function test_fails_validation_if_video_not_attached_to_range(): void
    {
        $caravanRange = $this->createRange();
        $video = factory(Video::class)->create();
        $data = $this->validFields([
            'video_id' => $video->id,
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('video_id');
    }

    public function test_dispatches_events()
    {
        Event::fake();
        $caravanRange = $this->createRange();
        $data = $this->validFields();

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        Event::assertDispatched(CaravanSaved::class);
    }

    public function test_does_sync_site_without_prices()
    {
        $caravanRange = $this->createRange();
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['sites'][0]['id'] = $site->id;

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $caravan = Caravan::first();
        $this->assertDatabaseHas('caravan_site', [
            'site_id' => $site->id,
            'caravan_id' => $caravan->id,
            'price' => null,
            'recommended_price' => null,
        ]);
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $caravanRange = $this->createRange();
        $data = $this->validFields([
            $inputName => null,
        ]);

        $response = $this->submit($caravanRange, $data);

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
            ['axles'],
            ['description'],
            ['year'],
            ['live'],
        ];
    }

    public function test_layout_id_exists()
    {
        $caravanRange = $this->createRange();
        $data = $this->validFields([
            'layout_id' => 140,
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('layout_id');
    }

    public function test_day_floorplan_is_an_image()
    {
        $caravanRange = $this->createRange();
        $data = $this->validFields([
            'day_floorplan' => 'abc',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('day_floorplan');
    }

    public function test_night_floorplan_is_an_image()
    {
        $caravanRange = $this->createRange();
        $data = $this->validFields([
            'night_floorplan' => 'abc',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('night_floorplan');
    }

    public function test_name_is_unique_based_on_year_and_caravan_range_id()
    {
        $caravanRange = $this->createRange();
        $caravan = $this->createCaravan([
            'caravan_range_id' => $caravanRange->id,
        ]);
        $data = $this->validFields([
            'name' => $caravan->name,
            'year' => $caravan->year,
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_axles_is_in_caravans_axles_array()
    {
        $caravanRange = $this->createRange();
        $data = $this->validFields([
            'axles' => 'wrong',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('axles');
    }

    public function test_stock_item_images_must_belong_to_range(): void
    {
        $caravanRange = $this->createRange();
        $media = factory(Media::class)->state('caravan-range')->create();
        $data = $this->validFields([
            'stock_item_image_ids' => [$media->id],
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('stock_item_image_ids.0');
    }

    private function submit(CaravanRange $caravanRange, array $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravanRange);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'axles' => Caravan::AXLE_SINGLE,
            'name' => 'some name',
            'exclusive' => true,
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

    private function url(CaravanRange $caravanRange)
    {
        return route('admin.caravan-ranges.caravans.store', $caravanRange);
    }

    private function createRange()
    {
        return factory(CaravanRange::class)->create();
    }

    private function createCaravan($attributes = [])
    {
        return factory(Caravan::class)->create($attributes);
    }
}
