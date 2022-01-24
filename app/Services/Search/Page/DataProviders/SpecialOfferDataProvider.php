<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\SpecialOffer;
use UnexpectedValueException;

final class SpecialOfferDataProvider extends BaseDataProvider
{
    public const TYPE = 'Special Offer';

    protected function getContentData(): array
    {
        return [self::KEY_CONTENT => $this->generateContentString()];
    }

    protected function getTypeData(): array
    {
        return [self::KEY_TYPE => self::TYPE];
    }

    private function generateContentString(): string
    {
        $specialOffer = $this->page->pageable;

        if (is_null($specialOffer) || !is_a($specialOffer, SpecialOffer::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of SpecialOffer');
        }

        return strip_tags($specialOffer->content);
    }
}
