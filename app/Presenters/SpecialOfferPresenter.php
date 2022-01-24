<?php

namespace App\Presenters;

use JsonSerializable;
use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpecialOfferPresenter extends BasePresenter implements JsonSerializable
{
    public function squareImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('squareImage');
    }

    public function landscapeImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('landscapeImage');
    }

    public function jsonSerialize()
    {
        return $this->getWrappedObject()->jsonSerialize();
    }

    public function linkUrl(): ?string
    {
        $specialOffer = $this->getWrappedObject();
        if ($mediaUrl = $specialOffer->getFirstMediaUrl('document')) {
            return $mediaUrl;
        }
        if ($specialOffer->url) {
            return $specialOffer->url;
        }
        return null;
    }
}
