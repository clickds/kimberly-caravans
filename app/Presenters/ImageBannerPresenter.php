<?php

namespace App\Presenters;

use App\Models\ImageBanner;
use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageBannerPresenter extends BasePresenter
{
    public function hasContent(): bool
    {
        return !is_null($this->getWrappedObject()->content);
    }

    public function iconPaths(): array
    {
        switch ($this->getWrappedObject()->icon) {
            case ImageBanner::ICON_CARAVAN:
                return ['site.shared.svg-icons.caravan'];
            case ImageBanner::ICON_MOTORHOME:
                return ['site.shared.svg-icons.motorhome'];
            case ImageBanner::ICON_BOTH:
                return ['site.shared.svg-icons.motorhome', 'site.shared.svg-icons.caravan'];
            default:
                return [];
        }
    }

    public function contentContainerCssClasses(): string
    {
        $banner = $this->getWrappedObject();
        $classes = ['w-full', 'md:w-1/2', 'xl:w-2/5'];
        $classes[] = $banner->text_alignment;

        return implode(' ', $classes);
    }

    public function iconCssClasses(): string
    {
        $banner = $this->getWrappedObject();
        $classes = ['h-12', 'flex', 'justify-center'];
        $classes[] = 'text-' . $banner->content_text_colour;

        return implode(' ', $classes);
    }

    public function titleCssClasses(): string
    {
        $banner = $this->getWrappedObject();
        $classes = ['font-heading', 'p-4', 'bg-opacity-90', 'leading-tight'];
        $classes[] = 'text-' . $banner->title_text_colour;
        $classes[] = 'bg-' . $banner->title_background_colour;

        return implode(' ', $classes);
    }

    public function contentCssClasses(): string
    {
        $banner = $this->getWrappedObject();
        $classes = ['wysiwyg', 'p-4', 'bg-opacity-70'];
        $classes[] = 'text-' . $banner->content_text_colour;
        $classes[] = 'bg-' . $banner->content_background_colour;

        return implode(' ', $classes);
    }

    public function getImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }
}
