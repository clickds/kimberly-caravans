<?php

namespace Tests\Feature\Admin\Manufacturer\MotorhomeRanges;

use App\Models\MotorhomeRange;
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
        $range = $this->createMotorhomeRange();
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
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'image' => UploadedFile::fake()->image('avatar.jpg', 1920),
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer));
        $data = Arr::except($data, ['image']);
        $this->assertDatabaseHas('motorhome_ranges', array_merge($data, ['id' => $motorhomeRange->id]));
        $this->assertFileExists($motorhomeRange->getFirstMedia('mainImage')->getPath());
    }

    public function test_syncing_sites()
    {
        $old_site = $this->createSite();
        $new_site = $this->createSite();
        $motorhomeRange = $this->createMotorhomeRange();
        $motorhomeRange->sites()->sync($old_site);
        $page = $this->createPageForPageable($motorhomeRange, $old_site);

        $data = $this->validFields();
        $data['site_ids'] = [$new_site->id];

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.manufacturers.motorhome-ranges.index', $motorhomeRange->manufacturer));
        $this->assertDatabaseHas('pageable_site', [
            'site_id' => $new_site->id,
            'pageable_id' => $motorhomeRange->id,
            'pageable_type' => MotorhomeRange::class,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_id' => $motorhomeRange->id,
            'pageable_type' => MotorhomeRange::class,
            'site_id' => $old_site->id,
        ]);
    }

    public function test_name_is_unique()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $otherRange = $this->createMotorhomeRange([
            'manufacturer_id' => $motorhomeRange->manufacturer_id,
        ]);
        $data = $this->validFields([
            'name' => $otherRange->name,
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_image_is_an_image()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'image' => 'abc',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('image');
    }

    public function test_image_must_be_at_least_1920_px_wide()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'image' => UploadedFile::fake()->image('avatar.jpg', 1919),
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('image');
    }

    private function submit(MotorhomeRange $motorhomeRange, array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhomeRange->manufacturer, $motorhomeRange);

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

    private function url(Manufacturer $manufacturer, MotorhomeRange $motorhomeRange): string
    {
        return route('admin.manufacturers.motorhome-ranges.update', [
            'motorhome_range' => $motorhomeRange,
            'manufacturer' => $manufacturer,
        ]);
    }

    private function createMotorhomeRange($attributes = []): MotorhomeRange
    {
        return factory(MotorhomeRange::class)->create($attributes);
    }
}
