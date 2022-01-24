<?php

namespace App\Presenters\PanelType;

use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\Page;
use Illuminate\Support\Collection;

class ManufacturerSliderPresenter extends BasePanelPresenter
{
    private Collection $caravanManufacturerPages;
    private Collection $motorhomeManufacturerPages;

    public function motorhomeManufacturerPages(): Collection
    {
        if ($this->displayMotorhomes()) {
            return $this->getMotorhomeManufacturerPages();
        }
        return new Collection();
    }

    public function caravanManufacturerPages(): Collection
    {
        if ($this->displayCaravans()) {
            return $this->getCaravanManufacturerPages();
        }
        return new Collection();
    }

    private function getCaravanManufacturerPages(): Collection
    {
        if (!isset($this->caravanManufacturerPages)) {
            $this->caravanManufacturerPages = $this->fetchCaravanManufacturerPages();
        }
        return $this->caravanManufacturerPages;
    }

    private function getMotorhomeManufacturerPages(): Collection
    {
        if (!isset($this->motorhomeManufacturerPages)) {
            $this->motorhomeManufacturerPages = $this->fetchMotorhomeManufacturerPages();
        }
        return $this->motorhomeManufacturerPages;
    }

    private function fetchCaravanManufacturerPages(): Collection
    {
        $newManufacturerIds = $this->fetchCaravanManufacturerIds();
        return Page::live()->published()->notExpired()->where('site_id', $this->getSite()->id)
            ->where('template', Page::TEMPLATE_MANUFACTURER_CARAVANS)
            ->whereIn('pageable_id', $newManufacturerIds)
            ->with('parent:id,slug')
            ->with([
                'pageable' => function ($query) {
                    return $query->with('logo');
                }
            ])
            ->select('id', 'parent_id', 'slug', 'pageable_type', 'pageable_id', 'template')
            ->get();
    }

    private function fetchCaravanManufacturerIds(): Collection
    {
        return CaravanRange::select('manufacturer_id')
            ->distinct('manufacturer_id')
            ->pluck('manufacturer_id');
    }

    private function fetchMotorhomeManufacturerPages(): Collection
    {
        $newManufacturerIds = $this->fetchMotorhomeManufacturerIds();
        return Page::live()->published()->notExpired()->where('site_id', $this->getSite()->id)
            ->where('template', Page::TEMPLATE_MANUFACTURER_MOTORHOMES)
            ->whereIn('pageable_id', $newManufacturerIds)
            ->with('parent:id,slug')
            ->with([
                'pageable' => function ($query) {
                    return $query->with('logo');
                }
            ])
            ->select('id', 'parent_id', 'slug', 'pageable_type', 'pageable_id', 'template')
            ->get();
    }

    private function fetchMotorhomeManufacturerIds(): Collection
    {
        return MotorhomeRange::select('manufacturer_id')
            ->distinct('manufacturer_id')
            ->pluck('manufacturer_id');
    }
}
