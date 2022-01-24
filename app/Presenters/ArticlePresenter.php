<?php

namespace App\Presenters;

use Carbon\Carbon;
use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArticlePresenter extends BasePresenter
{
    public function getImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }

    public function formattedDate(): string
    {
        if ($date = $this->getWrappedObject()->date) {
            return $date->format('j F Y');
        }
        return "";
    }

    public function publishedDate(): string
    {
        return $this->getWrappedObject()->published_at ? $this->getWrappedObject()->published_at->format('d-m-Y') : '';
    }

    public function publishedStatus(): string
    {
        $date = $this->getWrappedObject()->published_at;
        if (is_null($date)) {
            return 'Draft';
        }
        if ($date->lte(Carbon::now())) {
            return 'Published';
        }
        return 'Pending';
    }
}
