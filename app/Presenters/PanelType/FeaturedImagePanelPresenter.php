<?php

namespace App\Presenters\PanelType;

use App\Models\Panel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FeaturedImagePanelPresenter extends BasePanelPresenter
{
    public function getFeaturedImage(): ?Media
    {
        return $this->getPanel()->getFirstMedia('featuredImage');
    }

    public function getFeaturedImageContent(): string
    {
        return $this->getPanel()->featured_image_content ?: "";
    }

    public function leftOverlayPosition(): bool
    {
        return $this->getPanel()->overlay_position === Panel::OVERLAY_LEFT;
    }
}
