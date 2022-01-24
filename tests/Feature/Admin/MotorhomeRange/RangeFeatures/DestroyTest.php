<?php

namespace Tests\Feature\Admin\MotorhomeRange\RangeFeatures;

use App\Models\MotorhomeRange;
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

        $response->assertRedirect(route('admin.motorhome-ranges.range-features.index', $range));
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
        return route('admin.motorhome-ranges.range-features.destroy', [
            'motorhomeRange' => $rangeFeature->vehicleRange,
            'range_feature' => $rangeFeature,
        ]);
    }

    private function createRangeFeature(MotorhomeRange $motorhomeRange, $attributes = [])
    {
        $attributes = array_merge($attributes, [
            'vehicle_range_id' => $motorhomeRange->id,
            'vehicle_range_type' => MotorhomeRange::class,
        ]);
        return factory(RangeFeature::class)->create($attributes);
    }

    private function createRange($attributes = []): MotorhomeRange
    {
        return factory(MotorhomeRange::class)->create($attributes);
    }
}
