<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\CaravanStockItem;
use App\Presenters\SitePresenter;
use App\Services\Search\Page\DataProviders\CaravanStockItemDataProvider;
use App\Presenters\StockItem\CaravanPresenter;

class CaravanStockItemDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $caravanStockItem = factory(CaravanStockItem::class)->create();
        $page = $this->createPageForPageable($caravanStockItem, $site);
        $dataProvider = new CaravanStockItemDataProvider($page);

        $sitePresenter = (new SitePresenter())->setWrappedObject($page->site);
        $presenter = (new CaravanPresenter())->setWrappedObject($caravanStockItem);
        $expectedName = sprintf('%s (%s)', $page->name, $presenter->formattedPrice($sitePresenter));

        $this->assertEquals(
            $this->getExpectedData(
                $page,
                'Caravan Stock Item',
                $caravanStockItem->description,
                [
                    CaravanStockItemDataProvider::KEY_NAME => $expectedName,
                    CaravanStockItemDataProvider::KEY_CONDITION => $caravanStockItem->condition,
                ]
            ),
            $dataProvider->generateSiteSearchData()
        );
    }
}