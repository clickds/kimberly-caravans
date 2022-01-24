<?php

namespace Tests\Unit\Services\Manufacturer\MotorhomeRange;

use App\Models\MotorhomeRange;
use App\Models\Manufacturer;
use App\Models\RangeFeature;
use App\Models\RangeSpecificationSmallPrint;
use App\Models\Site;
use App\Services\Manufacturer\MotorhomeRange\MotorhomeRangeCloner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class MotorhomeRangeClonerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_clones_motorhome_range()
    {
        $rangeToClone = $this->createMotorhomeRangeToClone();
        $newRangeData = $this->getValidMotorhomeRangeData();

        $cloner = new MotorhomeRangeCloner($rangeToClone, $newRangeData);
        $newRange = $cloner->clone();
        $this->assertDatabaseHas('motorhome_ranges', $newRangeData);

        $this->assertFeaturesAreClonedCorrectly($rangeToClone, $newRange);
        $this->assertSpecificationSmallPrintsAreClonedCorrectly($rangeToClone, $newRange);
        $this->assertGalleryImagesAreClonedCorrectly($rangeToClone, $newRange);
    }

    private function assertFeaturesAreClonedCorrectly(MotorhomeRange $rangeToClone, MotorhomeRange $newRange): void
    {
        $rangeToClone->features->map(function (RangeFeature $feature) use ($newRange) {
            $expectedNewRangeFeatureData = $feature->getAttributes();
            $expectedNewRangeFeatureData['vehicle_range_id'] = $newRange->id;

            $this->assertDatabaseHas(
                'range_features',
                Arr::except($expectedNewRangeFeatureData, ['id', 'created_at', 'updated_at'])
            );
        });
    }

    private function assertSpecificationSmallPrintsAreClonedCorrectly(MotorhomeRange $rangeToClone, MotorhomeRange $newRange): void
    {
        $rangeToClone->specificationSmallPrints->map(function (RangeSpecificationSmallPrint $specificationSmallPrint) use ($newRange) {
            $expectedNewRangeSpecificationSmallPrintData = $specificationSmallPrint->getAttributes();
            $expectedNewRangeSpecificationSmallPrintData['vehicle_range_id'] = $newRange->id;

            $this->assertDatabaseHas(
                'range_specification_small_prints',
                Arr::except($expectedNewRangeSpecificationSmallPrintData, ['id', 'created_at', 'updated_at'])
            );
        });
    }

    private function assertGalleryImagesAreClonedCorrectly(MotorhomeRange $rangeToClone, MotorhomeRange $newRange): void
    {
        /**
         * Create dummy image files and associate the files with each gallery type.
         * Need to ensure the url is reachable in the tests as the cloner uses the addMediaFromUrl method.
         */
        $this->markTestIncomplete();
    }

    private function createMotorhomeRangeToClone(): MotorhomeRange
    {
        $manufacturer = factory(Manufacturer::class)->create();
        $sites = factory(Site::class, 2)->create();

        $motorhomeRange = factory(MotorhomeRange::class)->create();

        $motorhomeRange
            ->sites()
            ->sync($sites->pluck('id')->toArray());

        $motorhomeRange
            ->manufacturer()
            ->associate($manufacturer);

        $motorhomeRange
            ->features()
            ->saveMany(factory(RangeFeature::class, 2)->make());

        $motorhomeRange
            ->specificationSmallPrints()
            ->saveMany(factory(RangeSpecificationSmallPrint::class, 2)->make());

        return $motorhomeRange;
    }

    private function getValidMotorhomeRangeData(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'overview' => $this->faker->sentence(rand(7, 50)),
            'position' => $this->faker->randomDigit,
        ];
    }
}