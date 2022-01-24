<?php

namespace Tests\Feature\Admin\CaravanRange\RangeFeatures;

use App\Models\CaravanRange;
use App\Models\RangeFeature;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_updates_a_feature_successfully(): void
    {
        $range = $this->createRange();
        $rangeFeature = $this->createRangeFeature($range);
        $data = $this->validData();

        $response = $this->submit($rangeFeature, $data);

        $response->assertRedirect(route('admin.caravan-ranges.range-features.index', $range));
        $this->assertDatabaseHas('range_features', $data);
    }

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $range = $this->createRange();
        $rangeFeature = $this->createRangeFeature($range);
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($rangeFeature, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['name'],
            ['content'],
            ['key'],
            ['warranty'],
        ];
    }

    /**
     * @dataProvider booleanValidationProvider
     */
    public function test_boolean_validation(string $inputName): void
    {
        $range = $this->createRange();
        $rangeFeature = $this->createRangeFeature($range);
        $data = $this->validData([
            $inputName => 'abc',
        ]);

        $response = $this->submit($rangeFeature, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function booleanValidationProvider(): array
    {
        return [
            ['key'],
            ['warranty'],
        ];
    }

    public function test_site_exists_validation(): void
    {
        $range = $this->createRange();
        $rangeFeature = $this->createRangeFeature($range);
        $data = $this->validData([
            'site_ids' => [0],
        ]);

        $response = $this->submit($rangeFeature, $data);

        $response->assertSessionHasErrors('site_ids.0');
    }

    private function submit(RangeFeature $rangeFeature, array $data = []): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($rangeFeature);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'content' => 'some content',
            'key' => true,
            'name' => 'some name',
            'position' => 1,
            'warranty' => true,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(RangeFeature $rangeFeature): string
    {
        return route('admin.caravan-ranges.range-features.update', [
            'caravanRange' => $rangeFeature->vehicleRange,
            'range_feature' => $rangeFeature,
        ]);
    }

    private function createRangeFeature(CaravanRange $caravanRange, $attributes = [])
    {
        $attributes = array_merge($attributes, [
            'vehicle_range_id' => $caravanRange->id,
            'vehicle_range_type' => CaravanRange::class,
        ]);
        return factory(RangeFeature::class)->create($attributes);
    }

    private function createRange($attributes = []): CaravanRange
    {
        return factory(CaravanRange::class)->create($attributes);
    }
}
