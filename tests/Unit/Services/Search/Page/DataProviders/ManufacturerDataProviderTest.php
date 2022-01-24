<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Manufacturer;
use App\Services\Search\Page\DataProviders\ManufacturerDataProvider;

class ManufacturerDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $manufacturer = factory(Manufacturer::class)->create();
        $page = $this->createPageForPageable($manufacturer, $site);

        $dataProvider = new ManufacturerDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Manufacturer', $manufacturer->exclusive ? 'Exclusive to Marquis' : ''),
            $dataProvider->generateSiteSearchData()
        );
    }
}