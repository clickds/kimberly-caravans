<?php

namespace Tests\Feature\Admin\MotorhomeRange\RangeSpecificationSmallPrint;

use App\Models\MotorhomeRange;
use App\Models\RangeSpecificationSmallPrint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroying_small_print(): void
    {
        $smallPrint = $this->createSmallPrint();

        $response = $this->submit($smallPrint);

        $redirectUrl = $this->redirectUrl($smallPrint->vehicleRange);
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseMissing('range_specification_small_prints', $smallPrint->getAttributes());
    }

    private function submit(RangeSpecificationSmallPrint $smallPrint): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.motorhome-ranges.range-specification-small-prints.destroy', [
            'motorhomeRange' => $smallPrint->vehicleRange,
            'range_specification_small_print' => $smallPrint,
        ]);

        return $this->actingAs($user)->delete($url);
    }

    private function redirectUrl(MotorhomeRange $motorhomeRange)
    {
        route('admin.motorhome-ranges.range-specification-small-prints.index', $motorhomeRange);
    }

    private function createSmallPrint(array $attributes = []): RangeSpecificationSmallPrint
    {
        return factory(RangeSpecificationSmallPrint::class)->states('motorhome-range')
            ->create($attributes);
    }
}
