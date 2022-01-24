<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Site;

class CaravanPresenter extends BasePresenter
{
    private ?Media $dayFloorplan;
    private ?Media $nightFloorplan;
    private Collection $bedSizes;
    private Collection $rangeSpecificationSmallPrints;
    private ?Site $caravanSite;

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
        if ($range = $this->getWrappedObject()->caravanRange) {
            return $range->name;
        }
        return "";
    }

    public function price(SitePresenter $site): ?float
    {
        if ($caravanSite = $this->getCaravanSite($site)) {
            return $caravanSite->getRelationValue('pivot')->price;
        }
        return null;
    }

    public function recommendedPrice(SitePresenter $site): ?float
    {
        if ($caravanSite = $this->getCaravanSite($site)) {
            return $caravanSite->getRelationValue('pivot')->recommended_price;
        }
        return null;
    }

    public function formattedPrice(SitePresenter $site): string
    {
        $price = $this->price($site);

        if (is_null($price)) {
            return sprintf('%s%s', $site->currencySymbol(), 'TBA');
        }

        return $site->currencySymbol() . number_format($price);
    }

    public function formattedRecommendedPrice(SitePresenter $site): string
    {
        $recommendedPrice = $this->recommendedPrice($site);

        if (is_null($recommendedPrice)) {
            return sprintf('%s%s', $site->currencySymbol(), 'TBA');
        }

        return $site->currencySymbol() . number_format($recommendedPrice);
    }

    public function formattedSaving(SitePresenter $site): string
    {
        $price = $this->price($site);
        $recommendedPrice = $this->recommendedPrice($site);

        if (is_null($price) || is_null($recommendedPrice)) {
            return sprintf('%s%s', $site->currencySymbol(), 'TBA');
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

    public function berthString(): string
    {
        return $this->getWrappedObject()->berths->map->number->implode('/');
    }

    public function formattedName(): string
    {
        $caravan = $this->getWrappedObject();

        return $caravan->shouldPrependRangeName()
            ? sprintf('%s %s', $this->rangeName(), $caravan->name)
            : $caravan->name;
    }

    public function stockFormDetails(): string
    {
        $caravan = $this->getWrappedObject();

        $details = [
            $caravan->year,
            $this->manufacturerName(),
        ];

        if ($caravan->shouldPrependRangeName()) {
            $details[] = $this->rangeName() . ' ' . $caravan->name;
        } else {
            $details[] = $caravan->name;
        }

        return implode(' ', array_filter($details));
    }

    public function manufacturerName(): string
    {
        $caravan = $this->getWrappedObject();
        $range = $caravan->caravanRange;
        if (is_null($range)) {
            return '';
        }
        $manufacturer = $range->manufacturer;
        if (is_null($manufacturer)) {
            return '';
        }
        return $manufacturer->name;
    }

    private function getAttributeWithSuffix(string $attribute, string $suffix): string
    {
        $caravan = $this->getWrappedObject();
        if ($value = $caravan->$attribute) {
            return $value . $suffix;
        }
        return 'TBA';
    }

    private function getCaravanSite(SitePresenter $site): ?Site
    {
        if (!isset($this->caravanSite)) {
            $this->caravanSite = $this->fetchCaravanSite($site);
        }
        return $this->caravanSite;
    }

    /**
     * The site got via the caravan has a pivot with price on.
     */
    private function fetchCaravanSite(SitePresenter $sitePresenter): ?Site
    {
        return $this->getWrappedObject()->sites()->where('id', $sitePresenter->getWrappedObject()->id)->first();
    }
}
