<?php

namespace Tests\Unit\Services\Manufacturer\CaravanRange;

use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\RangeFeature;
use App\Models\RangeSpecificationSmallPrint;
use App\Models\Site;
use App\Services\Manufacturer\CaravanRange\CaravanRangeCloner;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CaravanRangeClonerTest extends TestCase
{
    use WithFaker;

    public function test_clones_caravan_range()
    {
        $rangeToClone = $this->createCaravanRangeToClone();
        $newRangeData = $this->getValidCaravanRangeData();

        $cloner = new CaravanRangeCloner($rangeToClone, $newRangeData);
        $newRange = $cloner->clone();
        $this->assertDatabaseHas('caravan_ranges', $newRangeData);

        $this->assertFeaturesAreClonedCorrectly($rangeToClone, $newRange);
        $this->assertSpecificationSmallPrintsAreClonedCorrectly($rangeToClone, $newRange);
        $this->assertGalleryImagesAreClonedCorrectly($rangeToClone, $newRange);
    }

    private function assertFeaturesAreClonedCorrectly(CaravanRange $rangeToClone, CaravanRange $newRange): void
    {
        $rangeToClone->features->map(function (RangeFeature $feature) use ($newRange) {
            $expectedNewRangeFeatureData = $feature->getAttributes();
            $expectedNewRangeFeatureData['vehicle_range_id'] = $newRange->id;

            $this->assertDatabaseHas('range_features', Arr::except($expectedNewRangeFeatureData, ['id', 'created_at', 'updated_at']));
        });
    }

    private function assertSpecificationSmallPrintsAreClonedCorrectly(CaravanRange $rangeToClone, CaravanRange $newRange): void
    {
        $rangeToClone->specificationSmallPrints->map(function (RangeSpecificationSmallPrint $specificationSmallPrint) use ($newRange) {
            $expectedNewRangeSpecificationSmallPrintData = $specificationSmallPrint->getAttributes();
            $expectedNewRangeSpecificationSmallPrintData['vehicle_range_id'] = $newRange->id;

            $this->assertDatabaseHas('range_specification_small_prints', Arr::except($expectedNewRangeSpecificationSmallPrintData, ['id', 'created_at', 'updated_at']));
        });
    }

    private function assertGalleryImagesAreClonedCorrectly(CaravanRange $rangeToClone, CaravanRange $newRange): void
    {
        /**
         * Create dummy image files and associate the files with each gallery type.
         * Need to ensure the url is reachable in the tests as the cloner uses the addMediaFromUrl method.
         */
        $this->markTestIncomplete();
    }

    private function createCaravanRangeToClone(): CaravanRange
    {
        $manufacturer = factory(Manufacturer::class)->create();
        $sites = factory(Site::class, 2)->create();

        $caravanRange = factory(CaravanRange::class)->create();

        $caravanRange
            ->sites()
            ->sync($sites->pluck('id')->toArray());

        $caravanRange
            ->manufacturer()
            ->associate($manufacturer);

        $caravanRange
            ->features()
            ->saveMany(factory(RangeFeature::class, 2)->make());

        $caravanRange
            ->specificationSmallPrints()
            ->saveMany(factory(RangeSpecificationSmallPrint::class, 2)->make());

        return $caravanRange;
    }

    private function getValidCaravanRangeData(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'overview' => $this->faker->sentence(rand(7, 50)),
            'position' => $this->faker->randomDigit,
        ];
    }
}