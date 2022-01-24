<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\MotorhomeRange;
use UnexpectedValueException;

final class MotorhomeRangeDataProvider extends BaseDataProvider
{
    public const TYPE = 'Motorhome Range';

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
        $motorhomeRange = $this->page->pageable;

        if (is_null($motorhomeRange) || !is_a($motorhomeRange, MotorhomeRange::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of MotorhomeRange');
        }

        return $motorhomeRange->overview;
    }
}
