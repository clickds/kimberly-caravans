<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\CaravanRange;
use UnexpectedValueException;

final class CaravanRangeDataProvider extends BaseDataProvider
{
    public const TYPE = 'Caravan Range';

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
        $caravanRange = $this->page->pageable;

        if (is_null($caravanRange) || !is_a($caravanRange, CaravanRange::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of CaravanRange');
        }

        return $caravanRange->overview;
    }
}
