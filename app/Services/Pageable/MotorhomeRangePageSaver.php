<?php

namespace App\Services\Pageable;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\Log;

class MotorhomeRangePageSaver
{
    /**
     * @var \App\Models\Site
     */
     private $site;

    /**
     * @var \App\Models\MotorhomeRange
     */
    private $range;

    /**
     * @var \App\Models\Manufacturer
     */
    private $manufacturer;

    public function __construct(MotorhomeRange $range, Site $site)
    {
        $this->range = $range;
        $this->site = $site;
        $this->manufacturer = $this->fetchManufacturer($range);
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->saveMotorhomeRangePage();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
        }
    }

    private function saveMotorhomeRangePage(): void
    {
        $page = $this->findOrInitializeMotorhomeRangePage();
        $page->name = $this->getRange()->name;
        $page->meta_title = $this->getRange()->name;
        $page->live = $this->getRange()->live;
        $page->parent_id = $this->findOrCreateManufacturerMotorhomesPage()->id;
        if ($this->shouldChangeSlug()) {
            $page->slug = '';
        }
        $page->save();
    }

    private function shouldChangeSlug(): bool
    {
        $motorhome = $this->getRange();
        return $motorhome->wasChanged(['name']);
    }

    private function findOrInitializeMotorhomeRangePage(): Page
    {
        return $this->range->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_MOTORHOME_RANGE,
        ]);
    }

    private function findOrCreateManufacturerMotorhomesPage(): Page
    {
        $manufacturer = $this->getManufacturer();

        return $manufacturer->pages()->firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
            ],
            [
                'name' => $manufacturer->name . ' Motorhomes',
                'meta_title' => $manufacturer->name . ' Motorhomes',
            ]
        );
    }

    private function fetchManufacturer(MotorhomeRange $range): Manufacturer
    {
        $manufacturer = $this->range->manufacturer;
        if (is_null($manufacturer)) {
            throw new Exception("Manufacturer not found");
        }
        return $manufacturer;
    }

    private function getRange(): MotorhomeRange
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
