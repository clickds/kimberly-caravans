<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Manufacturer;
use UnexpectedValueException;

final class ManufacturerDataProvider extends BaseDataProvider
{
    public const TYPE = 'Manufacturer';

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
        $manufacturer = $this->page->pageable;

        if (is_null($manufacturer) || !is_a($manufacturer, Manufacturer::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of Manufacturer');
        }

        return $manufacturer->exclusive ? 'Exclusive to Marquis' : '';
    }
}
