<?php

namespace App\Services\Page;

use App\Models\Area;
use App\Models\Page;
use App\Models\Panel;
use Illuminate\Support\Arr;

final class PageCloner
{
    private Page $pageToClone;
    private array $newPageData;

    public function __construct(Page $pageToClone, array $newPageData)
    {
        $this->pageToClone = $pageToClone;
        $this->newPageData = $newPageData;
    }

    public function clone(): Page
    {
        $newPage = new Page($this->newPageData);
        $newPage->save();

        $this->syncImageBanners($newPage);
        $this->cloneAreasAndPanels($this->pageToClone, $newPage);

        return $newPage;
    }

    private function syncImageBanners(Page $page): void
    {
        $imageBannerIds = Arr::get($this->newPageData, 'image_banner_ids');
        $page->imageBanners()->sync($imageBannerIds);
    }

    private function cloneAreasAndPanels(Page $pageToClone, Page $newPage): void
    {
        $pageToClone->areas->map(function (Area $areaToClone) use ($newPage) {
            $newArea = $this->cloneArea($areaToClone);
            $newPage->areas()->save($newArea);

            $areaToClone->panels->map(function (Panel $panelToClone) use ($newArea) {
                $newPanel = $this->clonePanel($panelToClone);
                $newArea->panels()->save($newPanel);

                $this->cloneMediaFromPanel($panelToClone, $newPanel);
                $this->cloneSpecialOffersFromPanel($panelToClone, $newPanel);
                $this->cloneFeatureableFromPanel($panelToClone, $newPanel);
            });
        });
    }

    private function cloneArea(Area $areaToClone): Area
    {
        return new Area($areaToClone->getAttributes());
    }

    private function clonePanel(Panel $panelToClone): Panel
    {
        return new Panel($panelToClone->getAttributes());
    }

    private function cloneMediaFromPanel(Panel $panelToClone, Panel $newPanel): void
    {
        $image = $panelToClone->getFirstMedia('image');

        if (!is_null($image)) {
            $newPanel->addMediaFromUrl($image->getFullUrl())->toMediaCollection('image');
        }

        $featuredImage = $panelToClone->getFirstMedia('featuredImage');

        if (!is_null($featuredImage)) {
            $newPanel->addMediaFromUrl($featuredImage->getFullUrl())->toMediaCollection('featuredImage');
        }
    }

    private function cloneSpecialOffersFromPanel(Panel $panelToClone, Panel $newPanel): void
    {
        $specialOfferIds = $panelToClone->specialOffers->pluck('id')->toArray();

        $newPanel->specialOffers()->sync($specialOfferIds);
    }

    private function cloneFeatureableFromPanel(Panel $panelToClone, Panel $newPanel): void
    {
        $newPanel->featureable_type = $panelToClone->featureable_type;
        $newPanel->featureable_id = $panelToClone->featureable_id;

        $newPanel->save();
    }
}
