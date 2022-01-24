<?php

namespace App\Services\Wysiwyg;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage;

class ResponsiveImageUrlsGenerator
{
    private Media $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function call(): array
    {
        // responsive is the collection name
        $files = $this->media->responsiveImages('responsive')->files;
        // Returns the responsive images as width => url
        $imageUrls = $files->mapWithKeys(function (ResponsiveImage $responsiveImage) {
            return [
                $responsiveImage->width() => $responsiveImage->url(),
            ];
        });
        // Add the default key to the start
        $imageUrls->prepend($this->media->getUrl('responsive'), 'default');

        return $imageUrls->toArray();
    }
}
