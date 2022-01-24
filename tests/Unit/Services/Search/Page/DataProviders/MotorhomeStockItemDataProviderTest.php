<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\MotorhomeStockItem;
use App\Presenters\SitePresenter;
use App\Services\Search\Page\DataProviders\MotorhomeStockItemDataProvider;
use App\Presenters\StockItem\MotorhomePresenter;

class MotorhomeStockItemDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $motorhomeStockItem = factory(MotorhomeStockItem::class)->create();
        $page = $this->createPageForPageable($motorhomeStockItem, $site);
        $dataProvider = new MotorhomeStockItemDataProvider($page);

        $sitePresenter = (new SitePresenter())->setWrappedObject($page->site);
        $presenter = (new MotorhomePresenter())->setWrappedObject($motorhomeStockItem);
        $expectedName = sprintf('%s (%s)', $page->name, $presenter->formattedPrice($sitePresenter));

        $this->assertEquals(
            $this->getExpectedData(
                $page,
                'Motorhome Stock Item',
                $motorhomeStockItem->description,
                [
                    MotorhomeStockItemDataProvider::KEY_NAME => $expectedName,
                    MotorhomeStockItemDataProvider::KEY_CONDITION => $motorhomeStockItem->condition,
                ]
            ),
            $dataProvider->generateSiteSearchData()
        );
    }
}
