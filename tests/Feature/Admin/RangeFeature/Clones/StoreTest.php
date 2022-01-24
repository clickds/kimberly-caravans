<?php

namespace Tests\Feature\Admin\RangeFeature\Clones;

use App\Models\RangeFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_cloning_caravan_range_feature(): void
    {
        $rangeFeatureToClone = factory(RangeFeature::class)->state('caravan-range')->create();

        $response = $this->submit($rangeFeatureToClone);

        $this->assertDatabaseCount('range_features', 2);
        $clonedRangeFeature = RangeFeature::orderBy('id', 'desc')->first();
        $redirectUrl = route('admin.caravan-ranges.range-features.edit', [
            'caravanRange' => $clonedRangeFeature->vehicleRange,
            'range_feature' => $clonedRangeFeature,
        ]);
        $response->assertRedirect($redirectUrl);
    }

    public function test_cloning_motorhome_range_feature(): void
    {
        $rangeFeatureToClone = factory(RangeFeature::class)->state('motorhome-range')->create();

        $response = $this->submit($rangeFeatureToClone);

        $this->assertDatabaseCount('range_features', 2);
        $clonedRangeFeature = RangeFeature::orderBy('id', 'desc')->first();
        $redirectUrl = route('admin.motorhome-ranges.range-features.edit', [
            'motorhomeRange' => $clonedRangeFeature->vehicleRange,
            'range_feature' => $clonedRangeFeature,
        ]);
        $response->assertRedirect($redirectUrl);
    }

    private function submit(RangeFeature $rangeFeature): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.range-features.clones.store', $rangeFeature);

        return $this->actingAs($user)->post($url, []);
    }
}
