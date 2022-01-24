<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LogoPresenter extends BasePresenter
{
    public function linkUrl(): ?string
    {
        $logo = $this->getWrappedObject();
        $page = $logo->page;

        if ($page) {
            return pageLink($page);
        }

        return $logo->external_url;
    }

    public function image(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }
}
