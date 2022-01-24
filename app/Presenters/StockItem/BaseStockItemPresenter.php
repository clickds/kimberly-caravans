<?php

namespace App\Presenters\StockItem;

use App\Models\Video;
use App\Presenters\SitePresenter;
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\BasePresenter;

abstract class BaseStockItemPresenter extends BasePresenter
{
    abstract public function videoUrl(): ?string;
    abstract public function videoThumbnail(): ?string;
    abstract public function floorplans(): Collection;
    abstract public function photos(): Collection;
    abstract public function title(): string;
    abstract public function otrInformation(): string;

    public function stockReference(): string
    {
        if (empty($this->getUniqueCode())) {
            return "";
        }
        return 'Stock Ref: ' . $this->getUniqueCode();
    }

    public function stockFormDetails(): string
    {
        $parts = [
            $this->title(),
        ];
        if ($this->getUniqueCode()) {
            $parts[] = '(' . $this->getUniqueCode() . ')';
        }

        return implode(' ', $parts);
    }

    public function formattedPrice(SitePresenter $site): string
    {
        return $site->currencySymbol() . number_format($this->getWrappedObject()->price);
    }

    public function formattedRecommendedPrice(SitePresenter $site): string
    {
        return $site->currencySymbol() . number_format($this->getWrappedObject()->recommended_price);
    }

    public function formattedPriceReduction(SitePresenter $site): string
    {
        return $site->currencySymbol() . number_format($this->getWrappedObject()->priceReduction());
    }

    public function formattedRegistrationDate(): string
    {
        if ($this->getWrappedObject()->registration_date) {
            return $this->getWrappedObject()->registration_date->format('Y-m-d');
        }
        return "";
    }

    protected function getAttributeWithSuffix(string $attribute, string $suffix): string
    {
        $vehicle = $this->getWrappedObject();
        if ($value = $vehicle->$attribute) {
            return $value . $suffix;
        }
        return 'TBA';
    }

    private function getUniqueCode(): ?string
    {
        return $this->getWrappedObject()->unique_code;
    }
}
