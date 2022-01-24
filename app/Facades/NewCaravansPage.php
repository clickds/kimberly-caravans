<?php

namespace App\Facades;

use App\Models\CaravanRange;
use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PopUp;
use App\Services\PageFetchers\ManufacturerCaravan as ManufacturerPagesFetcher;

class NewCaravansPage extends BasePage
{
    private Collection $manufacturerPages;
    private Collection $exclusivePages;
    private Collection $otherManufacturerPages;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->manufacturerPages = $this->fetchManufacturerPages();
        $this->exclusivePages = $this->fetchExclusivePages();
        $this->otherManufacturerPages = $this->fetchOtherManufacturerPages();
    }

    public function getTabNames(): array
    {
        $tabNames = ['all'];

        if ($this->exclusivePages->isNotEmpty()) {
            $tabNames[] = 'exclusive';
        }

        if ($this->otherManufacturerPages->isNotEmpty()) {
            $tabNames[] = 'other';
        }

        return $tabNames;
    }

    public function getExclusivePages(): Collection
    {
        return $this->exclusivePages;
    }

    public function getOtherManufacturerPages(): Collection
    {
        return $this->otherManufacturerPages;
    }

    public function getManufacturerPages(): Collection
    {
        return $this->manufacturerPages;
    }

    public function firstEligiblePopUp(): ?PopUp
    {
        $pagePopUp = $this->fetchEligiblePopUpForPage();
        $newCaravanPagePopUp = $this->fetchEligiblePopUpForNewCaravanPages();
        $allPagesPopUp = $this->fetchEligiblePopUpForAllPages();

        if (!is_null($pagePopUp)) {
            return $pagePopUp;
        }

        if (!is_null($newCaravanPagePopUp)) {
            return $newCaravanPagePopUp;
        }

        return $allPagesPopUp;
    }

    private function fetchManufacturerPages(): Collection
    {
        $fetcher = new ManufacturerPagesFetcher($this->getSite());
        $pages = $fetcher->call();
        $pages->load('pageable', 'pageable.caravanImage');
        return $pages->sortBy(function ($page) {
            return $page->pageable->caravan_position;
        });
    }

    private function fetchExclusivePages(): Collection
    {
        $manufacturerPages = $this->fetchExclusiveManufacturerPages();
        $rangePages = $this->fetchExclusiveRangePages();

        return $manufacturerPages->merge($rangePages)->sortBy('name');
    }

    private function fetchExclusiveManufacturerPages(): Collection
    {
        return $this->manufacturerPages->filter(function (Page $page) {
            $manufacturer = $page->pageable;
            if ($manufacturer instanceof Manufacturer) {
                return $manufacturer->exclusive;
            }
            return false;
        });
    }

    private function fetchExclusiveRangePages(): Collection
    {
        return Page::forSite($this->getSite())
            ->where('template', Page::TEMPLATE_CARAVAN_RANGE)
            ->displayable()
            ->where('pageable_type', CaravanRange::class)
            ->join('caravan_ranges', 'caravan_ranges.id', '=', 'pages.pageable_id')
            ->where('caravan_ranges.exclusive', true)
            ->select('pages.id', 'pages.slug', 'pages.parent_id', 'pages.pageable_type', 'pages.pageable_id', 'pages.template')
            ->with('pageable.tabContentImage')
            ->distinct()
            ->get();
    }

    private function fetchOtherManufacturerPages(): Collection
    {
        return $this->manufacturerPages->filter(function (Page $page) {
            $manufacturer = $page->pageable;
            if ($manufacturer instanceof Manufacturer) {
                return false === $manufacturer->exclusive;
            }
            return true;
        });
    }

    private function fetchEligiblePopUpForNewCaravanPages(): ?PopUp
    {
        $dismissedPopUpIds = $this->fetchDismissedPopUpIds();

        $query = PopUp::displayable()->where('appears_on_new_caravan_pages', true);

        if (!empty($dismissedPopUpIds)) {
            $query->whereNotIn('id', $dismissedPopUpIds);
        }

        return $query->first();
    }
}
