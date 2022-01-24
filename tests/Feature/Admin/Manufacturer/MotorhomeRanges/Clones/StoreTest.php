<?php

namespace Tests\Feature\Admin\Manufacturer\MotorhomeRanges\Clones;

use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use App\Models\MotorhomeRange;
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
        $motorhomeRange = $this->createMotorhomeRange();

        $newRangeData = $this->validFields([$requiredFieldName => null]);

        $response = $this->submit($manufacturer, $motorhomeRange, $newRangeData);

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
        $motorhomeRange = $this->createMotorhomeRange();

        $newRangeData = $this->validFields();
        $response = $this->submit($manufacturer, $motorhomeRange, $newRangeData);

        $response->assertRedirect(route('admin.manufacturers.motorhome-ranges.index', $manufacturer));

        // Ensure cloned range still exists
        $this->assertDatabaseHas('motorhome_ranges', ['id' => $motorhomeRange->id]);

        // Ensure new range has been created
        $data = Arr::except($newRangeData, ['image']);
        $this->assertDatabaseHas('motorhome_ranges', $data);

        $newMotorhomeRange = $manufacturer->motorhomeRanges()->orderBy('id', 'desc')->firstOrFail();
        $this->assertFileExists($newMotorhomeRange->getFirstMedia('mainImage')->getPath());
    }

    public function test_name_is_unique()
    {
        $manufacturer = $this->createManufacturer();
        $motorhomeRange = $this->createMotorhomeRange([
            'manufacturer_id' => $manufacturer->id,
        ]);

        $newRangeData = $this->validFields([
            'name' => $motorhomeRange->name,
        ]);

        $response = $this->submit($manufacturer, $motorhomeRange, $newRangeData);

        $response->assertSessionHasErrors('name');
    }

    private function submit(Manufacturer $manufacturer, MotorhomeRange $motorhomeRange, array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($manufacturer, $motorhomeRange);

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

    private function url(Manufacturer $manufacturer, MotorhomeRange $motorhomeRange): string
    {
        return route('admin.manufacturers.motorhome-ranges.store', [
            'manufacturer' => $manufacturer,
            'motorhomeRange' => $motorhomeRange,
        ]);
    }

    private function createManufacturer(): Manufacturer
    {
        return factory(Manufacturer::class)->create();
    }

    private function createMotorhomeRange($attributes = []): MotorhomeRange
    {
        return factory(MotorhomeRange::class)->create($attributes);
    }
}
