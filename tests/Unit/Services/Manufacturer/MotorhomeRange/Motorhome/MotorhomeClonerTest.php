<?php

namespace Tests\Unit\Services\Manufacturer\MotorhomeRange\Motorhome;

use App\Models\MotorhomeRange;
use App\Models\Manufacturer;
use App\Models\Motorhome;
use App\Services\Manufacturer\MotorhomeRange\Motorhome\MotorhomeCloner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class MotorhomeClonerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_clones_motorhomes()
    {
        $rangeWithMotorhomesToClone = $this->createMotorhomeRangeWithMotorhomesToClone();
        $targetRange = $this->createMotorhomeRange();

        $cloner = new MotorhomeCloner($rangeWithMotorhomesToClone, $targetRange);
        $cloner->clone();

        $rangeWithMotorhomesToClone->motorhomes->map(function (Motorhome $motorhome) use ($targetRange) {
            $expectedNewMotorhomeData = $motorhome->getAttributes();
            $expectedNewMotorhomeData['motorhome_range_id'] = $targetRange->id;

            $this->assertDatabaseHas('motorhomes', Arr::except($expectedNewMotorhomeData, ['id', 'created_at', 'updated_at']));
        });
    }

    public function test_seats_are_cloned_correctly()
    {
        $this->markTestIncomplete();
    }

    public function test_berths_are_cloned_correctly()
    {
        $this->markTestIncomplete();
    }

    public function test_site_pricing_is_cloned_correctly()
    {
        $this->markTestIncomplete();
    }

    public function test_bed_sizes_are_cloned_correctly()
    {
        $this->markTestIncomplete();
    }

    public function test_optional_weights_are_cloned_correctly()
    {
        $this->markTestIncomplete();
    }

    private function createMotorhomeRangeWithMotorhomesToClone(): MotorhomeRange
    {
        $manufacturer = factory(Manufacturer::class)->create();
        $motorhomeRange = factory(MotorhomeRange::class)->create();
        $motorhomeRange->manufacturer()->associate($manufacturer);

        $motorhomeRange->motorhomes()->saveMany(
            factory(Motorhome::class, 2)->create(['motorhome_range_id' => $motorhomeRange->id])
        );

        return $motorhomeRange;
    }

    private function createMotorhomeRange(): MotorhomeRange
    {
        $manufacturer = factory(Manufacturer::class)->create();

        $motorhomeRange = factory(MotorhomeRange::class)->create(['manufacturer_id' => $manufacturer->id]);

        return $motorhomeRange;
    }
}