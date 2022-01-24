<?php

namespace App\Presenters\StockItem;

use App\Models\MotorhomeRange;
use App\Models\FeedStockItemVideo;
use App\Presenters\SitePresenter;
use Illuminate\Support\Collection;

class MotorhomePresenter extends BaseStockItemPresenter
{
    public function title(): string
    {
        $stockItem = $this->getWrappedObject();

        $parts = [
            $stockItem->manufacturerName(),
        ];

        if (!is_null($stockItem->motorhome) && $stockItem->motorhome->shouldPrependRangeName()) {
            $parts[] = $stockItem->motorhome->motorhomeRange->name;
        }

        $parts[] = $stockItem->model;

        return implode(' ', array_filter($parts));
    }

    public function otrInformation(): string
    {
        $stockItem = $this->getWrappedObject();

        if ($stockItem->isNew()) {
            return 'NEW vehicle prices are OTR (On the Road) and include number plates,
            all delivery charges, road fund licence and the first registration fee.';
        }

        if ($stockItem->isUsed()) {
            return 'USED vehicle prices include preparation and number plates.';
        }

        return '';
    }

    public function videoUrl(): ?string
    {
        $video = $this->getVideo();
        if (is_null($video)) {
            return null;
        }
        if (get_class($video) === FeedStockItemVideo::class) {
            return $video->youtube_url;
        }
        return $video->embedCodeUrl();
    }

    public function videoThumbnail(): ?string
    {
        $video = $this->getVideo();
        if (is_null($video)) {
            return null;
        }
        return $video->getFirstMediaUrl('image', 'thumbStockSlider');
    }

    /**
     * @return mixed
     */
    private function getVideo()
    {
        $stockItem = $this->getWrappedObject();
        $motorhome = $stockItem->motorhome;
        if (is_null($motorhome)) {
            return $stockItem->feedStockItemVideo;
        }
        return $motorhome->video;
    }

    public function warrantyFeatures(SitePresenter $sitePresenter): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return collect();
        }
        $motorhome = $stockItem->motorhome;
        if ($motorhome) {
            return $motorhome->features()
                ->forSite($sitePresenter->getWrappedObject())
                ->where('warranty', true)
                ->orderBy('position', 'asc')->get();
        }
        return collect();
    }

    public function features(SitePresenter $sitePresenter): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return $stockItem->features;
        }
        $motorhome = $stockItem->motorhome;
        if ($motorhome) {
            return $motorhome->features()
                ->forSite($sitePresenter->getWrappedObject())
                ->where('key', true)
                ->orderBy('position', 'asc')->get();
        }
        return collect();
    }

    public function floorplans(): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return $stockItem->getMedia('layout');
        }
        $motorhome = $stockItem->motorhome;
        if (is_null($motorhome)) {
            return collect();
        }
        return $motorhome->media()->whereIn('collection_name', ['dayFloorplan', 'nightFloorplan'])->get();
    }

    public function photos(): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return $stockItem->getMedia('images');
        }
        $motorhome = $stockItem->motorhome;
        if (is_null($motorhome)) {
            return collect();
        }
        $images = $motorhome->stockItemImages()->get();
        $exterior = $images->filter(function ($media) {
            return $media->collection_name === MotorhomeRange::GALLERY_EXTERIOR;
        });
        $interior = $images->filter(function ($media) {
            return $media->collection_name === MotorhomeRange::GALLERY_INTERIOR;
        });
        $feature = $images->filter(function ($media) {
            return $media->collection_name === MotorhomeRange::GALLERY_FEATURE;
        });
        return $exterior->concat($interior)->concat($feature);
    }

    public function mainDetails(): array
    {
        $stockItem = $this->getWrappedObject();

        $parts = [];

        if ($berthString = $this->berthString()) {
            $parts[] = 'Berths: ' . $berthString;
        }
        $parts[] = $stockItem->condition;
        $parts[] = $stockItem->transmission;
        if ($stockItem->mileage) {
            $parts[] = $stockItem->mileage . ' miles';
        }

        return array_filter($parts);
    }

    public function berthString(): ?string
    {
        $berths = $this->getWrappedObject()->berths;
        if ($berths->isEmpty()) {
            return null;
        }
        return $berths->map(function ($berth) {
            return $berth->number;
        })->implode('/');
    }

    public function seatString(): ?string
    {
        $seats = $this->getWrappedObject()->seats;
        if ($seats->isEmpty()) {
            return null;
        }
        return $seats->map(function ($seat) {
            return $seat->number;
        })->implode('/');
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

    public function showPageTabs(): array
    {
        $stockItem = $this->getWrappedObject();
        $tabs = ['specification', 'warranty', 'part exchange'];
        if ($stockItem->price || $stockItem->recommended_price) {
            $tabs[] = 'finance calculator';
        }
        return $tabs;
    }
}
