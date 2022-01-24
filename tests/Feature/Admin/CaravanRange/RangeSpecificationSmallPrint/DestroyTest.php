<?php

namespace Tests\Feature\Admin\CaravanRange\RangeSpecificationSmallPrint;

use App\Models\CaravanRange;
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
        $url = route('admin.caravan-ranges.range-specification-small-prints.destroy', [
            'caravanRange' => $smallPrint->vehicleRange,
            'range_specification_small_print' => $smallPrint,
        ]);

        return $this->actingAs($user)->delete($url);
    }

    private function redirectUrl(CaravanRange $caravanRange)
    {
        route('admin.caravan-ranges.range-specification-small-prints.index', $caravanRange);
    }

    private function createSmallPrint(array $attributes = []): RangeSpecificationSmallPrint
    {
        return factory(RangeSpecificationSmallPrint::class)->states('caravan-range')
            ->create($attributes);
    }
}
