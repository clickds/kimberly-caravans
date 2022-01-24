<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;

class BrochurePresenter extends BasePresenter
{
    public function linkUrl(): ?string
    {
        $brochure = $this->getWrappedObject();
        if ($brochure->url) {
            return $brochure->url;
        }
        $media = $brochure->getFirstMedia('brochure_file');
        if ($media) {
            return $media->getUrl();
        }
        return null;
    }
}
