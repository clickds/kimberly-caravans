<?php

namespace App\Presenters;

use App\Models\Site;
use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Collection;

class MotorhomePresenter extends BasePresenter
{
    private ?Media $dayFloorplan;
    private ?Media $nightFloorplan;
    private Collection $bedSizes;
    private Collection $rangeSpecificationSmallPrints;
    private ?Site $motorhomeSite;

    public function hasFloorplan(): bool
    {
        return !is_null($this->dayFloorplan()) || !is_null($this->nightFloorplan());
    }

    public function dayFloorplan(): ?Media
    {
        if (!isset($this->dayFloorplan)) {
            $this->dayFloorplan = $this->getWrappedObject()->getFirstMedia('dayFloorplan');
        }
        return $this->dayFloorplan;
    }

    public function nightFloorplan(): ?Media
    {
        if (!isset($this->nightFloorplan)) {
            $this->nightFloorplan = $this->getWrappedObject()->getFirstMedia('nightFloorplan');
        }
        return $this->nightFloorplan;
    }

    public function bedSizes(): Collection
    {
        if (!isset($this->bedSizes)) {
            $this->bedSizes = $this->getWrappedObject()->bedSizes()
                ->with('bedDescription')->get();
        }
        return $this->bedSizes;
    }

    public function rangeSpecificationSmallPrints(Site $site): Collection
    {
        if (!isset($this->rangeSpecificationSmallPrints)) {
            $this->rangeSpecificationSmallPrints = $this->getWrappedObject()
                ->rangeSpecificationSmallPrints()
                ->where('site_id', $site->id)
                ->orderBy('position', 'asc')->get();
        }
        return $this->rangeSpecificationSmallPrints;
    }

    public function rangeName(): string
    {
        if ($range = $this->getWrappedObject()->motorhomeRange) {
            return $range->name;
        }
        return "";
    }

    public function price(SitePresenter $site): ?float
    {
        if ($motorhomeSite = $this->getMotorhomeSite($site)) {
            return $motorhomeSite->getRelationValue('pivot')->price;
        }
        return null;
    }

    public function recommendedPrice(SitePresenter $site): ?float
    {
        if ($motorhomeSite = $this->getMotorhomeSite($site)) {
            return $motorhomeSite->getRelationValue('pivot')->recommended_price;
        }
        return null;
    }

    public function formattedPrice(SitePresenter $site): string
    {
        $price = $this->price($site);

        if (is_null($price)) {
            return "£TBA";
        }

        return $site->currencySymbol() . number_format($price);
    }

    public function formattedRecommendedPrice(SitePresenter $site): string
    {
        $recommendedPrice = $this->recommendedPrice($site);

        if (is_null($recommendedPrice)) {
            return "£TBA";
        }

        return $site->currencySymbol() . number_format($recommendedPrice);
    }

    public function formattedSaving(SitePresenter $site): string
    {
        $price = $this->price($site);
        $recommendedPrice = $this->recommendedPrice($site);

        if (is_null($price) || is_null($recommendedPrice)) {
            return "£TBA";
        }

        $saving = bcsub($recommendedPrice, $price, 2);

        return $site->currencySymbol() . number_format($saving);
    }

    public function hasReducedPrice(SitePresenter $site): bool
    {
        $price = $this->price($site);
        $recommendedPrice = $this->recommendedPrice($site);

        if (is_null($price) || is_null($recommendedPrice)) {
            return false;
        }

        if ($price < $recommendedPrice) {
            return true;
        }

        return false;
    }

    public function berthString(): string
    {
        return $this->getWrappedObject()->berths->map->number->implode('/');
    }

    public function seatString(): string
    {
        return $this->getWrappedObject()->seats->map->number->implode('/');
    }

    private function getMotorhomeSite(SitePresenter $site): ?Site
    {
        if (!isset($this->motorhomeSite)) {
            $this->motorhomeSite = $this->fetchMotorhomeSite($site);
        }
        return $this->motorhomeSite;
    }

    public function formattedName(): string
    {
        $motorhome = $this->getWrappedObject();

        return $motorhome->shouldPrependRangeName()
            ? sprintf('%s %s', $this->rangeName(), $motorhome->name)
            : $motorhome->name;
    }

    public function stockFormDetails(): string
    {
        $motorhome = $this->getWrappedObject();

        $details = [
            $motorhome->year,
            $this->manufacturerName(),
        ];

        if ($motorhome->shouldPrependRangeName()) {
            $details[] = $this->rangeName() . ' ' . $motorhome->name;
        } else {
            $details[] = $motorhome->name;
        }

        return implode(' ', array_filter($details));
    }

    public function manufacturerName(): string
    {
        $motorhome = $this->getWrappedObject();
        $range = $motorhome->motorhomeRange;
        if (is_null($range)) {
            return '';
        }
        $manufacturer = $range->manufacturer;
        if (is_null($manufacturer)) {
            return '';
        }
        return $manufacturer->name;
    }

    /**
     * The site got via the motorhome has a pivot with price on.
     */
    private function fetchMotorhomeSite(SitePresenter $sitePresenter): ?Site
    {
        return $this->getWrappedObject()->sites()->where('id', $sitePresenter->getWrappedObject()->id)->first();
    }

    public function formattedLength(): string
    {
        return $this->getAttributeWithSuffix('length', 'm');
    }

    public function formattedWidth(): string
    {
        return $this->getAttributeWithSuffix('width', 'm');
    }

    public function formattedHeight(): string
    {
        return $this->getAttributeWithSuffix('height', 'm');
    }

    public function formattedHighLineHeight(): string
    {
        return $this->getAttributeWithSuffix('high_line_height', 'm');
    }

    public function formattedMro(): string
    {
        return $this->getAttributeWithSuffix('mro', 'kg');
    }

    public function formattedMtplm(): string
    {
        return $this->getAttributeWithSuffix('mtplm', 'kg');
    }

    public function formattedPayload(): string
    {
        return $this->getAttributeWithSuffix('payload', 'kg');
    }

    public function formattedGrossTrainWeight(): string
    {
        return $this->getAttributeWithSuffix('gross_train_weight', 'kg');
    }

    public function formattedMaximumTrailerWeight(): string
    {
        return $this->getAttributeWithSuffix('maximum_trailer_weight', 'kg');
    }

    private function getAttributeWithSuffix(string $attribute, string $suffix): string
    {
        $motorhome = $this->getWrappedObject();
        if ($value = $motorhome->$attribute) {
            return $value . $suffix;
        }
        return 'TBA';
    }
}
