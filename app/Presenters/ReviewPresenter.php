<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReviewPresenter extends BasePresenter
{
    private ?Media $reviewFile;

    public function image(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }

    public function formattedDate(): string
    {
        $date = $this->getWrappedObject()->date;
        if (is_null($date)) {
            return "";
        }
        return $date->format('j F Y');
    }

    public function linkUrl(): ?string
    {
        $review = $this->getWrappedObject();
        if ($file = $this->getReviewFile()) {
            return $file->getUrl();
        }
        return $review->link;
    }

    public function getReviewFile(): ?Media
    {
        if (!isset($this->reviewFile)) {
            $this->reviewFile = $this->getWrappedObject()->getFirstMedia('review_file');
        }
        return $this->reviewFile;
    }
}
