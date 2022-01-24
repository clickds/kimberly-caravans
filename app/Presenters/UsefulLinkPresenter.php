<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UsefulLinkPresenter extends BasePresenter
{
    public function getImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }
}
