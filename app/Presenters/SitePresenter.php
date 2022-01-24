<?php

namespace App\Presenters;

use JsonSerializable;
use McCool\LaravelAutoPresenter\BasePresenter;

class SitePresenter extends BasePresenter implements JsonSerializable
{
    public function flagUrl(): string
    {
        return '/images/flags/' . $this->getWrappedObject()->flag;
    }

    public function url(): string
    {
        return $this->getWrappedObject()->subdomain . '.marquisleisure.co.uk';
    }

    public function currencySymbol(bool $returnHtmlEntity = true): string
    {
        switch ($this->getWrappedObject()->flag) {
            case 'ireland.svg':
                return $returnHtmlEntity
                    ? '&euro;'
                    : '€';
            case 'new-zealand.svg':
                return $returnHtmlEntity
                    ? '&dollar;'
                    : '$';
            default:
                return $returnHtmlEntity
                    ? '&pound;'
                    : '£';
        }
    }

    public function jsonSerialize(): array
    {
        return $this->getWrappedObject()->jsonSerialize();
    }
}
