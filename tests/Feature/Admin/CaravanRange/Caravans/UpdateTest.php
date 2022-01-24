<?php

namespace Tests\Feature\Admin\CaravanRange\Caravans;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\CaravanSaved;
use App\Models\Berth;
use App\Models\Caravan;
use App\Models\CaravanRange;
use App\Models\Layout;
use App\Models\Site;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->fakeStorage();
        $caravan = $this->createCaravan();
        $berth = factory(Berth::class)->create();
        $data = $this->validFields([
            'day_floorplan' => UploadedFile::fake()->image('day_floorplan.jpg'),
            'night_floorplan' => UploadedFile::fake()->image('night_floorplan.jpg'),
            'berth_ids' => [$berth->id],
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        $data = Arr::except($data, ['day_floorplan', 'night_floorplan', 'berth_ids']);
        $this->assertDatabaseHas('caravans', $data);
        $this->assertDatabaseHas('berth_caravan', [
            'berth_id' => $berth->id,
            'caravan_id' => $caravan->id,
        ]);
        $this->assertFileExists($caravan->getFirstMedia('dayFloorplan')->getPath());
        $this->assertFileExists($caravan->getFirstMedia('nightFloorplan')->getPath());
    }

    public function test_syncing_stock_item_images(): void
    {
        $caravan = $this->createCaravan();
        $stockImage = factory(Media::class)->create([
            'model_type' => CaravanRange::class,
            'model_id' => $caravan->caravan_range_id,
        ]);
        $data = $this->validFields([
            'stock_item_image_ids' => [$stockImage->id],
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        $this->assertDatabaseHas('caravan_stock_item_images', [
            'media_id' => $stockImage->id,
        ]);
    }

    public function test_syncing_sites()
    {
        $caravan = $this->createCaravan();
        $site = factory(Site::class)->create();
        $old_site = $this->createSite();
        $new_site = $this->createSite();
        $caravan->sites()->sync($old_site);

        $data = $this->validFields();
        $data['sites'][0]['id'] = $new_site->id;
        $data['sites'][0]['price'] = 500;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($caravan, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        $this->assertDatabaseHas('caravan_site', [
            'site_id' => $new_site->id,
            'caravan_id' => $caravan->id,
            'price' => "500.00",
            'recommended_price' => "400.00",
        ]);
        $this->assertDatabaseMissing('caravan_site', [
            'caravan_id' => $caravan->id,
            'site_id' => $old_site->id,
        ]);
    }

    public function test_does_not_sync_sites_without_id(): void
    {
        $caravan = $this->createCaravan();
        $site = factory(Site::class)->create();
        $old_site = $this->createSite();
        $new_site = $this->createSite();
        $caravan->sites()->sync($old_site);
        $page = $this->createPageForPageable($caravan, $old_site);

        $data = $this->validFields();
        $data['sites'][0]['price'] = 500;
        $data['sites'][0]['recommended_price'] = 400;

        $response = $this->submit($caravan, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        $this->assertDatabaseMissing('caravan_site', [
            'caravan_id' => $caravan->id,
            'price' => "500.00",
            'recommended_price' => "400.00",
        ]);
    }

    public function test_with_video(): void
    {
        $caravan = $this->createCaravan();
        $caravanRange = $caravan->caravanRange;
        $video = factory(Video::class)->create();
        $caravanRange->videos()->attach($video);
        $data = $this->validFields([
            'video_id' => $video->id,
        ]);

        $response = $this->submit($caravan, $data);

        $caravanData = Arr::except($data, ['day_floorplan', 'night_floorplan']);
        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravanRange));
        $this->assertDatabaseHas('caravans', $caravanData);
    }

    public function test_fails_validation_if_video_not_attached_to_range(): void
    {
        $caravan = $this->createCaravan();
        $video = factory(Video::class)->create();
        $data = $this->validFields([
            'video_id' => $video->id,
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('video_id');
    }

    public function test_dispatches_events()
    {
        Event::fake();
        $caravan = $this->createCaravan();
        $data = $this->validFields();

        $response = $this->submit($caravan, $data);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        Event::assertDispatched(CaravanSaved::class);
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName)
    {
        $caravan = $this->createCaravan();
        $data = $this->validFields([
            $inputName => null,
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['layout_id'],
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
        $caravan = $this->createCaravan();
        $data = $this->validFields([
            'layout_id' => 140,
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('layout_id');
    }

    public function test_day_floorplan_is_an_image()
    {
        $caravan = $this->createCaravan();
        $data = $this->validFields([
            'day_floorplan' => 'abc',
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('day_floorplan');
    }

    public function test_night_floorplan_is_an_image()
    {
        $caravan = $this->createCaravan();
        $data = $this->validFields([
            'night_floorplan' => 'abc',
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('night_floorplan');
    }

    public function test_name_is_unique_based_on_year_and_caravan_range_id()
    {
        $caravan = $this->createCaravan();
        $otherCaravan = $this->createCaravan([
            'caravan_range_id' => $caravan->caravan_range_id,
        ]);
        $data = $this->validFields([
            'name' => $otherCaravan->name,
            'year' => $otherCaravan->year,
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_axles_is_in_caravans_axles_array()
    {
        $caravan = $this->createCaravan();
        $data = $this->validFields([
            'axles' => 'wrong',
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('axles');
    }

    public function test_stock_item_images_must_belong_to_range(): void
    {
        $caravan = $this->createCaravan();
        $media = factory(Media::class)->state('caravan-range')->create();
        $data = $this->validFields([
            'stock_item_image_ids' => [$media->id],
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('stock_item_image_ids.0');
    }

    private function submit(Caravan $caravan, array $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravan);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'axles' => Caravan::AXLE_SINGLE,
            'name' => 'some name',
            'exclusive' => true,
            'position' => 0,
            'height_includes_aerial' => true,
            'height' => "5.24",
            'length' => "5.24",
            'width' => "5.24",
            'mro' => 250,
            'mtplm' => 350,
            'payload' => 100,
            'year' => 2019,
            'description' => 'Some description',
            'small_print' => 'Some small print',
            'live' => true,
        ];
        if (!array_key_exists('layout_id', $overrides)) {
            $defaults['layout_id'] = factory(Layout::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url(Caravan $caravan)
    {
        return route('admin.caravan-ranges.caravans.update', [
            'caravanRange' => $caravan->caravanRange,
            'caravan' => $caravan,
        ]);
    }

    private function createCaravan($attributes = [])
    {
        return factory(Caravan::class)->create($attributes);
    }
}
