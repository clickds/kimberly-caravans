<?php

namespace App\Services\Manufacturer\CaravanRange\Caravan;

use App\Events\CaravanSaved;
use App\Models\BedSize;
use App\Models\Caravan;
use App\Models\CaravanRange;

final class CaravanCloner
{
    private CaravanRange $rangeToClone;
    private CaravanRange $targetRange;

    public function __construct(CaravanRange $rangeToClone, CaravanRange $targetRange)
    {
        $this->rangeToClone = $rangeToClone;
        $this->targetRange = $targetRange;
    }

    public function clone(): void
    {
        $this->rangeToClone->caravans->map(function (Caravan $caravanToClone) {
            $caravanToCloneData = $caravanToClone->getAttributes();
            $newCaravan = new Caravan($caravanToCloneData);

            $this->targetRange->caravans()->save($newCaravan);

            $this->syncBerths($caravanToClone, $newCaravan);
            $this->cloneDayFloorplan($caravanToClone, $newCaravan);
            $this->cloneNightFloorplan($caravanToClone, $newCaravan);
            $this->cloneSitePricing($caravanToClone, $newCaravan);
            $this->cloneBedSizes($caravanToClone, $newCaravan);
            $this->cloneStockItemImageSelections($caravanToClone, $newCaravan);

            event(new CaravanSaved($newCaravan));
        });
    }

    private function cloneDayFloorplan(Caravan $caravanToClone, Caravan $newCaravan): void
    {
        $dayFloorplanImage = $caravanToClone->getFirstMedia('dayFloorplan');

        if (is_null($dayFloorplanImage)) {
            return;
        }

        $newCaravan->addMediaFromUrl($dayFloorplanImage->getFullUrl())->toMediaCollection('dayFloorplan');
    }

    private function cloneNightFloorplan(Caravan $caravanToClone, Caravan $newCaravan): void
    {
        $nightFloorplanImage = $caravanToClone->getFirstMedia('nightFloorplan');

        if (is_null($nightFloorplanImage)) {
            return;
        }

        $newCaravan->addMediaFromUrl($nightFloorplanImage->getFullUrl())->toMediaCollection('nightFloorplan');
    }

    private function syncBerths(Caravan $caravanToClone, Caravan $newCaravan): void
    {
        $caravanToCloneBerthIds = $caravanToClone->berths->pluck('id')->toArray();

        $newCaravan->berths()->sync($caravanToCloneBerthIds);
    }

    private function cloneSitePricing(Caravan $caravanToClone, Caravan $newCaravan): void
    {
        $caravanToCloneSitePricing = $caravanToClone->sites()->withPivot('price')->get();

        $newCaravanSitePricingData = $caravanToCloneSitePricing->mapWithKeys(function ($siteData) {
            return [$siteData->id => ['price' => $siteData->pivot->price]];
        })->toArray();

        $newCaravan->sites()->sync($newCaravanSitePricingData);
    }

    private function cloneStockItemImageSelections(Caravan $caravanToClone, Caravan $newCaravan): void
    {
        $caravanToCloneStockItemImageFilenames = $caravanToClone->stockItemImages->pluck('file_name')->toArray();

        $newCaravanImageIdsToUse = $this->targetRange
            ->galleryImages()
            ->whereIn('file_name', $caravanToCloneStockItemImageFilenames)
            ->pluck('id')
            ->toArray();

        $newCaravan->stockItemImages()->sync($newCaravanImageIdsToUse);
    }

    private function cloneBedSizes(Caravan $caravanToClone, Caravan $newCaravan): void
    {
        $caravanToClone->bedSizes->map(function (BedSize $bedSize) use ($newCaravan) {
            $existingBedSizeData = $bedSize->getAttributes();
            $newBedSize = new BedSize($existingBedSizeData);
            $newCaravan->bedSizes()->save($newBedSize);
        });
    }
}
