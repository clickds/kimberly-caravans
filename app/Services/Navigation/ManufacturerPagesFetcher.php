<?php

namespace App\Services\Navigation;

use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Collection;
use App\Services\PageFetchers\ManufacturerCaravan as CaravanPageFetcher;
use App\Services\PageFetchers\ManufacturerMotorhome as MotorhomePageFetcher;

class ManufacturerPagesFetcher
{
    private const EXCLUSIVE = 'Exclusive';
    private const NON_EXCLUSIVE = 'Non-Exclusive';

    private Collection $displayablePageIds;
    private Collection $allCaravanManufacturerPages;
    private Collection $allMotorhomeManufacturerPages;
    private Collection $exclusiveCaravanManufacturerPages;
    private Collection $exclusiveMotorhomeManufacturerPages;
    private Collection $otherCaravanManufacturerPages;
    private Collection $otherMotorhomeManufacturerPages;
    private Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
        $this->fetchCaravanManufacturerPages();
        $this->fetchMotorhomeManufacturerPages();
    }

    public function call(): array
    {
        return [
            'allMotorhomeManufacturerPages' => $this->allMotorhomeManufacturerPages,
            'exclusiveMotorhomeManufacturerPages' => $this->exclusiveMotorhomeManufacturerPages,
            'otherMotorhomeManufacturerPages' => $this->otherMotorhomeManufacturerPages,
            'allCaravanManufacturerPages' => $this->allCaravanManufacturerPages,
            'exclusiveCaravanManufacturerPages' => $this->exclusiveCaravanManufacturerPages,
            'otherCaravanManufacturerPages' => $this->otherCaravanManufacturerPages,
        ];
    }

    private function fetchCaravanManufacturerPages(): void
    {
        $fetcher = new CaravanPageFetcher($this->getSite());
        $pages = $fetcher->call();
        $sortedPages = $this->sortPages($pages);
        $groupedPages = $this->groupPagesByManufacturerExclusive($sortedPages);

        $this->allCaravanManufacturerPages = $pages;
        $this->exclusiveCaravanManufacturerPages = $groupedPages->get(self::EXCLUSIVE, new Collection());
        $this->otherCaravanManufacturerPages = $groupedPages->get(self::NON_EXCLUSIVE, new Collection());
    }

    private function fetchMotorhomeManufacturerPages(): void
    {
        $fetcher = new MotorhomePageFetcher($this->getSite());
        $pages = $fetcher->call();
        $sortedPages = $this->sortPages($pages);
        $groupedPages = $this->groupPagesByManufacturerExclusive($sortedPages);

        $this->allMotorhomeManufacturerPages = $pages;
        $this->exclusiveMotorhomeManufacturerPages = $groupedPages->get(self::EXCLUSIVE, new Collection());
        $this->otherMotorhomeManufacturerPages = $groupedPages->get(self::NON_EXCLUSIVE, new Collection());
    }

    private function groupPagesByManufacturerExclusive(Collection $pages): Collection
    {
        return $pages->groupBy(function ($page, $key) {
            return $page->pageable->exclusive ? self::EXCLUSIVE : self::NON_EXCLUSIVE;
        });
    }

    private function sortPages(Collection $pages): Collection
    {
        return $pages->sortBy(function ($page) {
            return $page->pageable->name;
        });
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}
