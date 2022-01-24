<?php

namespace Tests\Feature\Admin\Manufacturer\CaravanRanges\Clones;

use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use App\Models\CaravanRange;
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
        $caravanRange = $this->createCaravanRange();

        $newRangeData = $this->validFields([$requiredFieldName => null]);

        $response = $this->submit($manufacturer, $caravanRange, $newRangeData);

        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return [
            ['name'],
            ['prepend_range_name_to_model_names'],
            ['live'],
            ['exclusive'],
        ];
    }

    public function test_successful()
    {
        $this->fakeStorage();
        $manufacturer = $this->createManufacturer();
        $caravanRange = $this->createCaravanRange();

        $newRangeData = $this->validFields();
        $response = $this->submit($manufacturer, $caravanRange, $newRangeData);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $manufacturer));

        // Ensure cloned range still exists
        $this->assertDatabaseHas('caravan_ranges', ['id' => $caravanRange->id]);

        // Ensure new range has been created
        $data = Arr::except($newRangeData, ['image']);
        $this->assertDatabaseHas('caravan_ranges', $data);

        $newCaravanRange = $manufacturer->caravanRanges()->orderBy('id', 'desc')->firstOrFail();
        $this->assertFileExists($newCaravanRange->getFirstMedia('mainImage')->getPath());
    }

    public function test_name_is_unique()
    {
        $manufacturer = $this->createManufacturer();
        $caravanRange = $this->createCaravanRange([
            'manufacturer_id' => $manufacturer->id,
        ]);

        $newRangeData = $this->validFields([
            'name' => $caravanRange->name,
        ]);

        $response = $this->submit($manufacturer, $caravanRange, $newRangeData);

        $response->assertSessionHasErrors('name');
    }

    private function submit(Manufacturer $manufacturer, CaravanRange $caravanRange, array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($manufacturer, $caravanRange);

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

    private function url(Manufacturer $manufacturer, CaravanRange $caravanRange): string
    {
        return route('admin.manufacturers.caravan-ranges.store', [
            'manufacturer' => $manufacturer,
            'caravanRange' => $caravanRange,
        ]);
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
