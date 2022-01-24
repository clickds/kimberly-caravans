<?php

namespace App\Services\Manufacturer\CaravanRange;

use App\Models\CaravanRange;
use App\Models\RangeFeature;
use App\Models\RangeSpecificationSmallPrint;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UnexpectedValueException;

final class CaravanRangeCloner
{
    private CaravanRange $rangeToClone;
    private array $newRangeData;

    public function __construct(CaravanRange $rangeToClone, array $newRangeData)
    {
        $this->rangeToClone = $rangeToClone;
        $this->newRangeData = $newRangeData;
    }

    public function clone(): CaravanRange
    {
        $newRange = new CaravanRange($this->newRangeData);

        if (is_null($this->rangeToClone->manufacturer)) {
            throw new UnexpectedValueException(
                'Failed to clone caravan range. Expected manufacturer to be available.'
            );
        }

        $this->rangeToClone
            ->manufacturer
            ->caravanRanges()
            ->save($newRange);

        $this->syncSites($newRange);
        $this->saveCaravanRangeImage($newRange);
        $this->cloneFeatures($newRange);
        $this->cloneSpecificationSmallPrints($newRange);
        $this->cloneFeatureGalleryImages($newRange);
        $this->cloneInteriorGalleryImages($newRange);
        $this->cloneExteriorGalleryImages($newRange);

        return $newRange;
    }

    private function syncSites(CaravanRange $newRange): void
    {
        $siteIds = Arr::get($this->newRangeData, 'site_ids', []);
        $newRange->sites()->sync($siteIds);
    }

    /**
     * Check if the new range data contains an uploaded file. If not,
     * attempt to copy the main image from the range being cloned.
     */
    private function saveCaravanRangeImage(CaravanRange $newRange): void
    {
        $image = Arr::get($this->newRangeData, 'image');

        if (!is_null($image)) {
            $newRange->addMedia($image);
            return;
        }

        $existingMainImage = $this->rangeToClone->getFirstMedia('mainImage');

        if (is_null($existingMainImage)) {
            return;
        }

        $newRange->addMediaFromUrl($existingMainImage->getFullUrl())->toMediaCollection('mainImage');
    }

    private function cloneFeatures(CaravanRange $newRange): void
    {
        $this->rangeToClone->features->map(function (RangeFeature $rangeFeature) use ($newRange) {
            $existingFeatureData = $rangeFeature->getAttributes();
            $existingFeatureData = Arr::except($existingFeatureData, ['id']);
            $existingFeatureSites = $rangeFeature->sites->pluck('id')->toArray();

            $newFeature = new RangeFeature($existingFeatureData);
            $newRange->features()->save($newFeature);
            $newFeature->sites()->sync($existingFeatureSites);
        });
    }

    private function cloneSpecificationSmallPrints(CaravanRange $newRange): void
    {
        $this->rangeToClone
            ->specificationSmallPrints
            ->map(function (RangeSpecificationSmallPrint $rangeSpecificationSmallPrint) use ($newRange) {
                $existingSpecificationSmallPrintData = $rangeSpecificationSmallPrint->getAttributes();
                $existingSpecificationSmallPrintData = Arr::except($existingSpecificationSmallPrintData, ['id']);
                $newSpecificationSmallPrint = new RangeSpecificationSmallPrint($existingSpecificationSmallPrintData);
                $newRange->specificationSmallPrints()->save($newSpecificationSmallPrint);
            });
    }

    private function cloneFeatureGalleryImages(CaravanRange $newRange): void
    {
        $this->rangeToClone
            ->getMedia(CaravanRange::GALLERY_FEATURE)
            ->map(function (Media $featureImage) use ($newRange) {
                $existingFeatureImageUrl = $featureImage->getFullUrl();
                $newRange->addMediaFromUrl($existingFeatureImageUrl)->toMediaCollection(CaravanRange::GALLERY_FEATURE);
            });
    }

    private function cloneInteriorGalleryImages(CaravanRange $newRange): void
    {
        $this->rangeToClone
            ->getMedia(CaravanRange::GALLERY_INTERIOR)
            ->map(function (Media $interiorImage) use ($newRange) {
                $existingInteriorImageUrl = $interiorImage->getFullUrl();

                $newRange
                    ->addMediaFromUrl($existingInteriorImageUrl)
                    ->toMediaCollection(CaravanRange::GALLERY_INTERIOR);
            });
    }

    private function cloneExteriorGalleryImages(CaravanRange $newRange): void
    {
        $this->rangeToClone
            ->getMedia(CaravanRange::GALLERY_EXTERIOR)
            ->map(function (Media $exteriorImage) use ($newRange) {
                $existingExteriorImageUrl = $exteriorImage->getFullUrl();

                $newRange
                    ->addMediaFromUrl($existingExteriorImageUrl)
                    ->toMediaCollection(CaravanRange::GALLERY_EXTERIOR);
            });
    }
}
