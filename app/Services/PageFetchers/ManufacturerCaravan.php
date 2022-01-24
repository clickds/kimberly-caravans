<?php

namespace App\Services\PageFetchers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;

class ManufacturerCaravan
{
    private Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function call(): Collection
    {
        $manufacturerIds = $this->fetchManufacturerIds();

        return Page::with('pageable:id,name,exclusive', 'parent:id,slug')
            ->forSite($this->getSite())
            ->displayable()
            ->where('pageable_type', Manufacturer::class)
            ->whereIn('pageable_id', $manufacturerIds)
            ->where('template', Page::TEMPLATE_MANUFACTURER_CARAVANS)
            ->select('id', 'slug', 'parent_id', 'pageable_type', 'pageable_id', 'template')
            ->get();
    }

    private function fetchManufacturerIds(): SupportCollection
    {
        return $this->getSite()->manufacturers()->where(function ($query) {
            $query->whereHas('caravanRanges');
        })->toBase()->pluck('id');
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}
