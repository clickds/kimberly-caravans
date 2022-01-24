<?php

namespace App\ViewComposers;

use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\Navigation;
use App\Models\Page;
use App\Models\Site;
use App\Services\Navigation\MainNavigationFetcher;
use App\Services\Navigation\FullMenuNavigationFetcher;
use App\Services\Navigation\ManufacturerPagesFetcher;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class HeaderComposer
{
    private Navigation $mainNavigation;
    private Navigation $fullMenuNavigation;
    private Site $currentSite;
    private Collection $allCaravanManufacturerPages;
    private Collection $allMotorhomeManufacturerPages;
    private Collection $exclusiveCaravanManufacturerPages;
    private Collection $exclusiveMotorhomeManufacturerPages;
    private Collection $otherCaravanManufacturerPages;
    private Collection $otherMotorhomeManufacturerPages;
    private Collection $exclusiveCaravanRangePages;
    private Collection $exclusiveMotorhomeRangePages;
    private string $caravanComparisonPageUrl;
    private string $motorhomeComparisonPageUrl;
    private string $searchPageUrl;

    public function __construct()
    {
        $this->currentSite = $this->fetchSite();
        $pageIds = Page::forSite($this->currentSite)->displayable()->toBase()->pluck('id');

        $this->mainNavigation = $this->fetchMainNavigation($this->currentSite, $pageIds);
        $this->fullMenuNavigation = $this->fetchFullMenuNavigation($this->currentSite, $pageIds);
        $manufacturerPages = $this->fetchManufacturerPages($this->currentSite);
        list(
            'allMotorhomeManufacturerPages' => $this->allMotorhomeManufacturerPages,
            'exclusiveMotorhomeManufacturerPages' => $this->exclusiveMotorhomeManufacturerPages,
            'otherMotorhomeManufacturerPages' => $this->otherMotorhomeManufacturerPages,
            'allCaravanManufacturerPages' => $this->allCaravanManufacturerPages,
            'exclusiveCaravanManufacturerPages' => $this->exclusiveCaravanManufacturerPages,
            'otherCaravanManufacturerPages' => $this->otherCaravanManufacturerPages,
        ) = $manufacturerPages;
        $this->exclusiveCaravanRangePages = $this->fetchExclusiveCaravanRangePages();
        $this->exclusiveMotorhomeRangePages = $this->fetchExclusiveMotorhomeRangePages();
        $this->caravanComparisonPageUrl = $this->getCaravanComparisonPageUrl();
        $this->motorhomeComparisonPageUrl = $this->getMotorhomeComparisonPageUrl();
        $this->searchPageUrl = $this->getSearchPageUrl();
    }

    public function compose(View $view): void
    {
        $exclusiveCaravanPages = $this->exclusiveCaravanManufacturerPages
            ->merge($this->exclusiveCaravanRangePages)->sortBy('name');
        $exclusiveMotorhomePages = $this->exclusiveMotorhomeManufacturerPages
            ->merge($this->exclusiveMotorhomeRangePages)->sortBy('name');
        $view->with([
            'currentSite' => $this->currentSite,
            'currentOpeningTime' => $this->currentSite->currentOpeningTime(),
            'mainNavigation' => $this->mainNavigation,
            'fullMenuNavigation' => $this->fullMenuNavigation,
            'allMotorhomeManufacturerPages' => $this->allMotorhomeManufacturerPages,
            'exclusiveMotorhomePages' => $exclusiveMotorhomePages,
            'otherMotorhomeManufacturerPages' => $this->otherMotorhomeManufacturerPages,
            'allCaravanManufacturerPages' => $this->allCaravanManufacturerPages,
            'exclusiveCaravanPages' => $exclusiveCaravanPages,
            'otherCaravanManufacturerPages' => $this->otherCaravanManufacturerPages,
            'caravanComparisonPageUrl' => $this->caravanComparisonPageUrl,
            'motorhomeComparisonPageUrl' => $this->motorhomeComparisonPageUrl,
            'searchPageUrl' => $this->searchPageUrl,
        ]);
    }

    private function fetchSite(): Site
    {
        try {
            return resolve('currentSite');
        } catch (Throwable $e) {
            return new Site();
        }
    }

    private function fetchFullMenuNavigation(Site $site, Collection $pageIds): Navigation
    {
        $fetcher = new FullMenuNavigationFetcher($site, $pageIds);
        return $fetcher->call();
    }

    private function fetchMainNavigation(Site $site, Collection $pageIds): Navigation
    {
        $fetcher = new MainNavigationFetcher($site, $pageIds);
        return $fetcher->call();
    }

    private function fetchManufacturerPages(Site $site): array
    {
        $fetcher = new ManufacturerPagesFetcher($site);
        return $fetcher->call();
    }

    private function getCaravanComparisonPageUrl(): string
    {
        $page = Page::forSite($this->currentSite)
            ->where('template', Page::TEMPLATE_CARAVAN_COMPARISON)
            ->first();

        if (is_null($page)) {
            return '';
        }

        return pageLink($page);
    }

    private function getMotorhomeComparisonPageUrl(): string
    {
        $page = Page::forSite($this->currentSite)
            ->where('template', Page::TEMPLATE_MOTORHOME_COMPARISON)
            ->first();

        if (is_null($page)) {
            return '';
        }

        return pageLink($page);
    }

    private function getSearchPageUrl(): string
    {
        $page = Page::forSite($this->currentSite)
            ->where('template', Page::TEMPLATE_SEARCH)
            ->displayable()
            ->first();

        if (is_null($page)) {
            return '';
        }

        return pageLink($page);
    }

    private function fetchExclusiveCaravanRangePages(): Collection
    {
        return Page::forSite($this->currentSite)
            ->where('template', Page::TEMPLATE_CARAVAN_RANGE)
            ->displayable()
            ->where('pageable_type', CaravanRange::class)
            ->join('caravan_ranges', 'caravan_ranges.id', '=', 'pages.pageable_id')
            ->where('caravan_ranges.exclusive', true)
            ->select('pages.id', 'pages.slug', 'pages.parent_id', 'pages.pageable_type', 'pages.pageable_id', 'pages.template')
            ->distinct()
            ->get();
    }

    private function fetchExclusiveMotorhomeRangePages(): Collection
    {
        return Page::forSite($this->currentSite)
            ->where('template', Page::TEMPLATE_MOTORHOME_RANGE)
            ->displayable()
            ->where('pageable_type', MotorhomeRange::class)
            ->join('motorhome_ranges', 'motorhome_ranges.id', '=', 'pages.pageable_id')
            ->where('motorhome_ranges.exclusive', true)
            ->select('pages.id', 'pages.slug', 'pages.parent_id', 'pages.pageable_type', 'pages.pageable_id', 'pages.template')
            ->distinct()
            ->get();
    }
}
