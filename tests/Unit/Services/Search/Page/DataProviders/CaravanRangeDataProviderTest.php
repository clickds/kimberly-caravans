<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\CaravanRange;
use App\Services\Search\Page\DataProviders\CaravanRangeDataProvider;

class CaravanRangeDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $caravanRange = factory(CaravanRange::class)->create();
        $page = $this->createPageForPageable($caravanRange, $site);

        $dataProvider = new CaravanRangeDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Caravan Range', $caravanRange->overview),
            $dataProvider->generateSiteSearchData()
        );
    }
}