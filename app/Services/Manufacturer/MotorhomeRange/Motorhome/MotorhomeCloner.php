<?php

namespace App\Services\Manufacturer\MotorhomeRange\Motorhome;

use App\Events\MotorhomeSaved;
use App\Models\BedSize;
use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use App\Models\OptionalWeight;

final class MotorhomeCloner
{
    private MotorhomeRange $rangeToClone;
    private MotorhomeRange $targetRange;

    public function __construct(MotorhomeRange $rangeToClone, MotorhomeRange $targetRange)
    {
        $this->rangeToClone = $rangeToClone;
        $this->targetRange = $targetRange;
    }

    public function clone(): void
    {
        $this->rangeToClone->motorhomes->map(function (Motorhome $motorhomeToClone) {
            $motorhomeToCloneData = $motorhomeToClone->getAttributes();
            $newMotorhome = new Motorhome($motorhomeToCloneData);

            $this->targetRange->motorhomes()->save($newMotorhome);

            $this->syncBerths($motorhomeToClone, $newMotorhome);
            $this->syncSeats($motorhomeToClone, $newMotorhome);
            $this->cloneDayFloorplan($motorhomeToClone, $newMotorhome);
            $this->cloneNightFloorplan($motorhomeToClone, $newMotorhome);
            $this->cloneSitePricing($motorhomeToClone, $newMotorhome);
            $this->cloneBedSizes($motorhomeToClone, $newMotorhome);
            $this->cloneOptionalWeights($motorhomeToClone, $newMotorhome);
            $this->cloneStockItemImageSelections($motorhomeToClone, $newMotorhome);

            event(new MotorhomeSaved($newMotorhome));
        });
    }

    private function cloneDayFloorplan(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $dayFloorplanImage = $motorhomeToClone->getFirstMedia('dayFloorplan');

        if (is_null($dayFloorplanImage)) {
            return;
        }

        $newMotorhome->addMediaFromUrl($dayFloorplanImage->getFullUrl())->toMediaCollection('dayFloorplan');
    }

    private function cloneNightFloorplan(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $nightFloorplanImage = $motorhomeToClone->getFirstMedia('nightFloorplan');

        if (is_null($nightFloorplanImage)) {
            return;
        }

        $newMotorhome->addMediaFromUrl($nightFloorplanImage->getFullUrl())->toMediaCollection('nightFloorplan');
    }

    private function syncBerths(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $motorhomeToCloneBerthIds = $motorhomeToClone->berths->pluck('id')->toArray();

        $newMotorhome->berths()->sync($motorhomeToCloneBerthIds);
    }

    private function syncSeats(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $motorhomeToCloneSeatIds = $motorhomeToClone->seats->pluck('id')->toArray();

        $newMotorhome->seats()->sync($motorhomeToCloneSeatIds);
    }

    private function cloneSitePricing(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $motorhomeToCloneSitePricing = $motorhomeToClone->sites()->withPivot('price')->get();

        $newMotorhomeSitePricingData = $motorhomeToCloneSitePricing->mapWithKeys(function ($siteData) {
            return [$siteData->id => ['price' => $siteData->pivot->price]];
        })->toArray();

        $newMotorhome->sites()->sync($newMotorhomeSitePricingData);
    }

    private function cloneStockItemImageSelections(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $motorhomeToCloneStockItemImageFilenames = $motorhomeToClone->stockItemImages->pluck('file_name')->toArray();

        $newMotorhomeImageIdsToUse = $this->targetRange
            ->galleryImages()
            ->whereIn('file_name', $motorhomeToCloneStockItemImageFilenames)
            ->pluck('id')
            ->toArray();

        $newMotorhome->stockItemImages()->sync($newMotorhomeImageIdsToUse);
    }

    private function cloneBedSizes(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $motorhomeToClone->bedSizes->map(function (BedSize $bedSize) use ($newMotorhome) {
            $existingBedSizeData = $bedSize->getAttributes();
            $newBedSize = new BedSize($existingBedSizeData);
            $newMotorhome->bedSizes()->save($newBedSize);
        });
    }

    private function cloneOptionalWeights(Motorhome $motorhomeToClone, Motorhome $newMotorhome): void
    {
        $motorhomeToClone->optionalWeights->map(function (OptionalWeight $optionalWeight) use ($newMotorhome) {
            $existingOptionalWeightData = $optionalWeight->getAttributes();
            $newOptionalWeight = new OptionalWeight($existingOptionalWeightData);
            $newMotorhome->optionalWeights()->save($newOptionalWeight);
        });
    }
}
