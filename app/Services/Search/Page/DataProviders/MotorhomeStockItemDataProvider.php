<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\MotorhomeStockItem;
use App\Presenters\SitePresenter;
use UnexpectedValueException;
use App\Presenters\StockItem\MotorhomePresenter;

final class MotorhomeStockItemDataProvider extends BaseDataProvider
{
    public const TYPE = 'Motorhome Stock Item';
    public const KEY_NAME = 'name';
    public const KEY_CONDITION = 'condition';

    protected function getContentData(): array
    {
        return [self::KEY_CONTENT => $this->generateContentString()];
    }

    protected function getTypeData(): array
    {
        return [self::KEY_TYPE => self::TYPE];
    }

    protected function getTypeSpecificData(): array
    {
        $page = $this->page;
        $sitePresenter = (new SitePresenter())->setWrappedObject($page->site);
        $motorhomeStockItem = (new MotorhomePresenter())->setWrappedObject($this->getMotorhomeStockItem());

        return [
            self::KEY_NAME => sprintf('%s (%s)', $page->name, $motorhomeStockItem->formattedPrice($sitePresenter)),
            self::KEY_CONDITION => $motorhomeStockItem->condition,
        ];
    }

    private function generateContentString(): string
    {
        return $this->getMotorhomeStockItem()->description;
    }

    private function getMotorhomeStockItem(): MotorhomeStockItem
    {
        $motorhomeStockItem = $this->page->pageable;

        if (is_null($motorhomeStockItem) || !is_a($motorhomeStockItem, MotorhomeStockItem::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of MotorhomeStockItem');
        }

        return $motorhomeStockItem;
    }
}
