<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\CaravanStockItem;
use App\Presenters\SitePresenter;
use UnexpectedValueException;
use App\Presenters\StockItem\CaravanPresenter;

final class CaravanStockItemDataProvider extends BaseDataProvider
{
    public const TYPE = 'Caravan Stock Item';
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
        $caravanStockItem = (new CaravanPresenter())->setWrappedObject($this->getCaravanStockItem());

        return [
            self::KEY_NAME => sprintf('%s (%s)', $page->name, $caravanStockItem->formattedPrice($sitePresenter)),
            self::KEY_CONDITION => $caravanStockItem->condition,
        ];
    }

    private function generateContentString(): string
    {
        return $this->getCaravanStockItem()->description;
    }

    private function getCaravanStockItem(): CaravanStockItem
    {
        $caravanStockItem = $this->page->pageable;

        if (is_null($caravanStockItem) || !is_a($caravanStockItem, CaravanStockItem::class)) {
            throw new UnexpectedValueException('Expected pageable to be an instance of CaravanStockItem');
        }

        return $caravanStockItem;
    }
}
