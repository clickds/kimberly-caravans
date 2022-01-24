<?php

namespace App\Presenters\Page;

use App\Models\Manufacturer;
use App\Models\Page;
use App\Presenters\Interfaces\TabbableContent;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ManufacturerPresenter extends BasePagePresenter implements TabbableContent
{
    public function linkTitle(): string
    {
        return $this->manufacturerName();
    }

    public function manufacturerName(): string
    {
        return $this->getManufacturer()->name;
    }

    public function getLogo(): ?Media
    {
        return $this->getManufacturer()->logo;
    }

    public function getMedia(): ?Media
    {
        $associationName = $this->associationName();
        return $this->getManufacturer()->{$associationName};
    }

    private function getManufacturer(): Manufacturer
    {
        return $this->getWrappedObject()->pageable;
    }

    private function associationName(): string
    {
        if ($this->getWrappedObject()->hasTemplate(Page::TEMPLATE_MANUFACTURER_MOTORHOMES)) {
            return 'motorhomeImage';
        }
        return 'caravanImage';
    }
}
