<?php

namespace App\Presenters\Page;

use App\Models\MotorhomeRange;
use App\Presenters\Interfaces\TabbableContent;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Presenters\MotorhomeRangePresenter as ModelPresenter;

class MotorhomeRangePresenter extends BasePagePresenter implements TabbableContent
{
    public function linkTitle(): string
    {
        return $this->MotorhomeRangeName();
    }

    public function MotorhomeRangeName(): string
    {
        return $this->getMotorhomeRange()->name;
    }

    public function getMedia(): ?Media
    {
        return $this->getMotorhomeRange()->tabContentImage;
    }

    private function getMotorhomeRange(): MotorhomeRange
    {
        $pageable = $this->getWrappedObject()->pageable;

        if (get_class($pageable) === ModelPresenter::class) {
            $pageable = $pageable->getWrappedObject();
        }
        return $pageable;
    }
}
