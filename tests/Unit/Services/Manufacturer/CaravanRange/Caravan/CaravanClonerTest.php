<?php

namespace Tests\Unit\Services\Manufacturer\CaravanRange\Caravan;

use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\Caravan;
use App\Services\Manufacturer\CaravanRange\Caravan\CaravanCloner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CaravanClonerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_clones_caravans()
    {
        $rangeWithCaravansToClone = $this->createCaravanRangeWithCaravansToClone();
        $targetRange = $this->createCaravanRange();

        $cloner = new CaravanCloner($rangeWithCaravansToClone, $targetRange);
        $cloner->clone();

        $rangeWithCaravansToClone->caravans->map(function (Caravan $caravan) use ($targetRange) {
            $expectedNewCaravanData = $caravan->getAttributes();
            $expectedNewCaravanData['caravan_range_id'] = $targetRange->id;

            $this->assertDatabaseHas('caravans', Arr::except($expectedNewCaravanData, ['id', 'created_at', 'updated_at']));
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

    private function createCaravanRangeWithCaravansToClone(): CaravanRange
    {
        $manufacturer = factory(Manufacturer::class)->create();
        $caravanRange = factory(CaravanRange::class)->create();
        $caravanRange->manufacturer()->associate($manufacturer);

        $caravanRange->caravans()->saveMany(
            factory(Caravan::class, 2)->create(['caravan_range_id' => $caravanRange->id])
        );

        return $caravanRange;
    }

    private function createCaravanRange(): CaravanRange
    {
        $manufacturer = factory(Manufacturer::class)->create();

        $caravanRange = factory(CaravanRange::class)->create(['manufacturer_id' => $manufacturer->id]);

        return $caravanRange;
    }
}