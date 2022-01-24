<?php

namespace App\Presenters\StockItem;

use App\Models\CaravanRange;
use App\Models\FeedStockItemVideo;
use App\Presenters\SitePresenter;
use Illuminate\Support\Collection;

class CaravanPresenter extends BaseStockItemPresenter
{
    public function title(): string
    {
        $stockItem = $this->getWrappedObject();

        $parts = [
            $stockItem->manufacturerName(),
        ];

        if (!is_null($stockItem->caravan) && $stockItem->caravan->shouldPrependRangeName()) {
            $parts[] = $stockItem->caravan->caravanRange->name;
        }

        $parts[] = $stockItem->model;

        return implode(' ', array_filter($parts));
    }

    public function otrInformation(): string
    {
        $stockItem = $this->getWrappedObject();

        return $stockItem->isNew()
            ? 'NEW vehicle prices are OTR (On the Road) and include all delivery charges'
            : '';
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
        $caravan = $stockItem->caravan;
        if (is_null($caravan)) {
            return $stockItem->feedStockItemVideo;
        }
        return $caravan->video;
    }

    public function floorplans(): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return $stockItem->getMedia('layout');
        }
        $caravan = $stockItem->caravan;
        if (is_null($caravan)) {
            return collect();
        }
        return $caravan->media()->whereIn('collection_name', ['dayFloorplan', 'nightFloorplan'])->get();
    }

    public function warrantyFeatures(SitePresenter $sitePresenter): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return collect();
        }
        $caravan = $stockItem->caravan;
        if ($caravan) {
            return $caravan->features()
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
        $caravan = $stockItem->caravan;
        if ($caravan) {
            return $caravan->features()
                ->forSite($sitePresenter->getWrappedObject())
                ->where('key', true)
                ->orderBy('position', 'asc')->get();
        }
        return collect();
    }


    public function photos(): Collection
    {
        $stockItem = $this->getWrappedObject();
        if ($stockItem->hasFeedSource()) {
            return $stockItem->getMedia('images');
        }
        $caravan = $stockItem->caravan;
        if (is_null($caravan)) {
            return collect();
        }
        $images = $caravan->stockItemImages()->get();
        $exterior = $images->filter(function ($media) {
            return $media->collection_name === CaravanRange::GALLERY_EXTERIOR;
        });
        $interior = $images->filter(function ($media) {
            return $media->collection_name === CaravanRange::GALLERY_INTERIOR;
        });
        $feature = $images->filter(function ($media) {
            return $media->collection_name === CaravanRange::GALLERY_FEATURE;
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
