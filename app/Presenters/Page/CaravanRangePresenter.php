<?php

namespace App\Presenters\Page;

use App\Models\CaravanRange;
use App\Presenters\Interfaces\TabbableContent;
use App\Presenters\CaravanRangePresenter as ModelPresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CaravanRangePresenter extends BasePagePresenter implements TabbableContent
{
    public function linkTitle(): string
    {
        return $this->CaravanRangeName();
    }

    public function CaravanRangeName(): string
    {
        return $this->getCaravanRange()->name;
    }

    public function getMedia(): ?Media
    {
        return $this->getCaravanRange()->tabContentImage;
    }

    private function getCaravanRange(): CaravanRange
    {
        $pageable = $this->getWrappedObject()->pageable;

        if (get_class($pageable) === ModelPresenter::class) {
            $pageable = $pageable->getWrappedObject();
        }
        return $pageable;
    }
}
