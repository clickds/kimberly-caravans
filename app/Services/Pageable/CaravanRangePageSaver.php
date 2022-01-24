<?php

namespace App\Services\Pageable;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\Log;

class CaravanRangePageSaver
{
    /**
     * @var \App\Models\Site
     */
     private $site;

    /**
     * @var \App\Models\CaravanRange
     */
    private $range;

    /**
     * @var \App\Models\Manufacturer
     */
    private $manufacturer;

    public function __construct(CaravanRange $range, Site $site)
    {
        $this->range = $range;
        $this->site = $site;
        $this->manufacturer = $this->fetchManufacturer($range);
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->saveCaravanRangePage();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
        }
    }

    private function saveCaravanRangePage(): void
    {
        $page = $this->findOrInitializeCaravanRangePage();
        $page->name = $this->getRange()->name;
        $page->meta_title = $this->getRange()->name;
        $page->live = $this->getRange()->live;
        $page->parent_id = $this->findOrCreateManufacturerCaravansPage()->id;
        if ($this->shouldChangeSlug()) {
            $page->slug = '';
        }
        $page->save();
    }

    private function shouldChangeSlug(): bool
    {
        $caravan = $this->getRange();
        return $caravan->wasChanged(['name']);
    }

    private function findOrInitializeCaravanRangePage(): Page
    {
        return $this->range->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_CARAVAN_RANGE,
        ]);
    }

    private function findOrCreateManufacturerCaravansPage(): Page
    {
        $manufacturer = $this->getManufacturer();

        return $manufacturer->pages()->firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
            ],
            [
                'name' => $manufacturer->name . ' Caravans',
                'meta_title' => $manufacturer->name . ' Caravans',
            ]
        );
    }

    private function fetchManufacturer(CaravanRange $range): Manufacturer
    {
        $manufacturer = $this->range->manufacturer;
        if (is_null($manufacturer)) {
            throw new Exception("Manufacturer not found");
        }
        return $manufacturer;
    }

    private function getRange(): CaravanRange
    {
        return $this->range;
    }

    private function getManufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}
