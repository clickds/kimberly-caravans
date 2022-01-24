<?php

namespace App\Presenters\Cta;

use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;
use McCool\LaravelAutoPresenter\BasePresenter as BaseAutoPresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class BasePresenter extends BaseAutoPresenter
{
    abstract public function partialPath(): string;
    abstract public function page(): BasePagePresenter;

    public function cssClasses(): string
    {
        return 'h-full flex flex-col justify-between';
    }

    public function displayable(): bool
    {
        return true;
    }

    public function getImage(): ?Media
    {
        return $this->getWrappedObject()->getFirstMedia('image');
    }
}
