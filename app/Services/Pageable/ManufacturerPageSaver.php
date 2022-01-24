<?php

namespace App\Services\Pageable;

use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Page;
use App\Models\Manufacturer;
use App\Models\Site;

class ManufacturerPageSaver
{
    /**
     * @var \App\Models\Manufacturer
     */
    private $manufacturer;
    /**
     * @var \App\Models\Site
     */
    private $site;

    public function __construct(Manufacturer $manufacturer, Site $site)
    {
        $this->manufacturer = $manufacturer;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->saveCaravanPage();
            $this->saveMotorhomePage();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();
        }
    }

    private function saveCaravanPage(): void
    {
        $page = $this->findOrInitializeCaravanPage();
        $page->name = $this->getManufacturer()->name . " Caravans";
        $page->meta_title = $this->getManufacturer()->name . " Caravans";
        $page->parent_id = $this->findOrCreateNewCaravansPage()->id;
        $page->save();
    }

    private function saveMotorhomePage(): void
    {
        $page = $this->findOrInitializeMotorhomePage();
        $page->name = $this->getManufacturer()->name . " Motorhomes";
        $page->meta_title = $this->getManufacturer()->name . " Motorhomes";
        $page->parent_id = $this->findOrCreateNewMotorhomesPage()->id;
        $page->save();
    }

    private function findOrInitializeCaravanPage(): Page
    {
        return $this->getManufacturer()->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
        ]);
    }

    private function findOrInitializeMotorhomePage(): Page
    {
        return $this->getManufacturer()->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
        ]);
    }

    private function findOrCreateNewCaravansPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_NEW_CARAVANS,
            ],
            [
                'name' => 'New Caravans',
                'meta_title' => 'New Caravans',
            ]
        );
    }

    private function findOrCreateNewMotorhomesPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_NEW_MOTORHOMES,
            ],
            [
                'name' => 'New Motorhomes',
                'meta_title' => 'New Motorhomes',
            ]
        );
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
