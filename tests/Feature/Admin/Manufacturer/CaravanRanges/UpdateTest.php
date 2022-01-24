<?php

namespace Tests\Feature\Admin\Manufacturer\CaravanRanges;

use App\Models\CaravanRange;
use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider required_fields_provider
     */
    public function test_required_validation(string $requiredFieldName)
    {
        $range = $this->createCaravanRange();
        $data = $this->validFields([$requiredFieldName => null]);
        $response = $this->submit($range, $data);

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
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'image' => UploadedFile::fake()->image('avatar.jpg', 1920),
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer));
        $data = Arr::except($data, ['image']);
        $this->assertDatabaseHas('caravan_ranges', array_merge($data, ['id' => $caravanRange->id]));
        $this->assertFileExists($caravanRange->getFirstMedia('mainImage')->getPath());
    }

    public function test_syncing_sites()
    {
        $old_site = $this->createSite();
        $new_site = $this->createSite();
        $caravanRange = $this->createCaravanRange();
        $caravanRange->sites()->sync($old_site);
        $page = $this->createPageForPageable($caravanRange, $old_site);

        $data = $this->validFields();
        $data['site_ids'] = [$new_site->id];

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.manufacturers.caravan-ranges.index', $caravanRange->manufacturer));
        $this->assertDatabaseHas('pageable_site', [
            'site_id' => $new_site->id,
            'pageable_id' => $caravanRange->id,
            'pageable_type' => CaravanRange::class,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_id' => $caravanRange->id,
            'pageable_type' => CaravanRange::class,
            'site_id' => $old_site->id,
        ]);
    }

    public function test_name_is_unique()
    {
        $caravanRange = $this->createCaravanRange();
        $otherCaravanRange = $this->createCaravanRange([
            'manufacturer_id' => $caravanRange->manufacturer_id,
        ]);
        $data = $this->validFields([
            'name' => $otherCaravanRange->name,
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_image_is_an_image()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'image' => 'abc',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('image');
    }

    public function test_image_must_be_at_least_1920_px_wide()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'image' => UploadedFile::fake()->image('avatar.jpg', 1919),
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('image');
    }

    private function submit(CaravanRange $caravanRange, array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravanRange->manufacturer, $caravanRange);

        return $this->actingAs($admin)->put($url, $data);
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
        return route('admin.manufacturers.caravan-ranges.update', [
            'caravan_range' => $caravanRange,
            'manufacturer' => $manufacturer,
        ]);
    }

    private function createCaravanRange($attributes = []): CaravanRange
    {
        return factory(CaravanRange::class)->create($attributes);
    }
}
