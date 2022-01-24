<?php

namespace Tests\Feature\Admin\CaravanRange\RangeFeatures;

use App\Models\CaravanRange;
use App\Models\RangeFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletes_a_feature_successfully(): void
    {
        $range = $this->createRange();
        $rangeFeature = $this->createRangeFeature($range);

        $response = $this->submit($rangeFeature);

        $response->assertRedirect(route('admin.caravan-ranges.range-features.index', $range));
        $this->assertDatabaseMissing('range_features', $rangeFeature->getAttributes());
    }

    private function submit(RangeFeature $rangeFeature): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($rangeFeature);

        return $this->actingAs($user)->delete($url);
    }

    private function url(RangeFeature $rangeFeature): string
    {
        return route('admin.caravan-ranges.range-features.destroy', [
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
