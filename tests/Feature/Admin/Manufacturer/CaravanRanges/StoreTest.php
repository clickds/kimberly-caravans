<?php

namespace Tests\Feature\Admin\Manufacturer\CaravanRanges;

use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider required_fields_provider
     */
    public function test_required_validation(string $requiredFieldName)
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validFields([$requiredFieldName => null]);
        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return [
            ['name'],
            ['prepend_range_name_to_model_names'],
            ['image'],
            ['exclusive'],
            ['live'],
        ];
    }

    public function test_successful()
    {
        $this->fakeStorage();
        $manufacturer = $this->createManufacturer();
        $data = $this->validFields([
            'image' => UploadedFile::fake()->image('avatar.jpg', 1920),
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $manufacturer));
        $data = Arr::except($data, ['image']);
        $this->assertDatabaseHas('caravan_ranges', $data);
        $caravanRange = $manufacturer->caravanRanges()->first();
        $this->assertFileExists($caravanRange->getFirstMedia('mainImage')->getPath());
    }

    public function test_syncing_sites()
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validFields();
        $site = factory(Site::class)->create();
        $data['site_ids'][] = $site->id;

        $response = $this->submit($manufacturer, $data);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $manufacturer));
        $caravanRange = CaravanRange::first();
        $this->assertDatabaseHas('pageable_site', [
            'site_id' => $site->id,
            'pageable_id' => $caravanRange->id,
            'pageable_type' => CaravanRange::class,
        ]);
    }

    public function test_name_is_unique()
    {
        $manufacturer = $this->createManufacturer();
        $caravanRange = $this->createCaravanRange([
            'manufacturer_id' => $manufacturer->id,
        ]);
        $data = $this->validFields([
            'name' => $caravanRange->name,
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_image_is_an_image()
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validFields([
            'image' => 'abc',
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors('image');
    }

    public function test_image_must_be_at_least_1920_px_wide()
    {
        $manufacturer = $this->createManufacturer();
        $data = $this->validFields([
            'image' => UploadedFile::fake()->image('avatar.jpg', 1919),
        ]);

        $response = $this->submit($manufacturer, $data);

        $response->assertSessionHasErrors('image');
    }

    private function submit(Manufacturer $manufacturer, array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url($manufacturer);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'prepend_range_name_to_model_names' => false,
            'overview' => 'some overview',
            'image' => UploadedFile::fake()->image('avatar.jpg', 1920),
            'live' => true,
            'exclusive' => true,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Manufacturer $manufacturer): string
    {
        return route('admin.manufacturers.caravan-ranges.store', $manufacturer);
    }

    private function createManufacturer(): Manufacturer
    {
        return factory(Manufacturer::class)->create();
    }

    private function createCaravanRange($attributes = []): CaravanRange
    {
        return factory(CaravanRange::class)->create($attributes);
    }
}
