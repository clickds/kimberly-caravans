<?php

namespace App\Presenters\Interfaces;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * For content displayed in tabs on the new motorhomes and new caravans page
 */
interface TabbableContent
{
    public function linkTitle(): string;
    public function getMedia(): ?Media;
    public function link(): string;
}
