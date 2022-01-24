<?php

namespace App\Presenters;

use App\Presenters\Page\BasePagePresenter;
use McCool\LaravelAutoPresenter\BasePresenter;

class ButtonPresenter extends BasePresenter
{
    public function link(): string
    {
        $button = $this->getWrappedObject();
        if ($button->external_url) {
            return $button->external_url;
        }

        $presenter = (new BasePagePresenter())->setWrappedObject($button->linkPage);
        return $presenter->link();
    }

    public function linkTarget(): string
    {
        if ($this->hasExternalUrl()) {
            return '_blank';
        }
        return '_self';
    }

    public function cssClasses(): string
    {
        $classes = [
            'block',
            'p-2',
            'text-center',
            'text-white',
            'rounded',
            'font-heading',
            'font-semibold',
        ];

        $button = $this->getWrappedObject();
        $classes[] = 'bg-' . $button->colour;

        return implode(' ', $classes);
    }

    private function hasExternalUrl(): bool
    {
        return null !== $this->getWrappedObject()->external_url;
    }
}
