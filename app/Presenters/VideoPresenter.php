<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VideoPresenter extends BasePresenter
{
    public function getImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }

    public function limitedExcerpt(): string
    {
        return Str::limit($this->getWrappedObject()->excerpt, 200);
    }

    public function formattedDate(): string
    {
        if ($date = $this->getWrappedObject()->published_at) {
            return $date->format('j F Y');
        }
        return "";
    }
}
