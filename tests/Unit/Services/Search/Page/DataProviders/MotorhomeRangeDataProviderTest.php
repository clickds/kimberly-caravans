<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\MotorhomeRange;
use App\Services\Search\Page\DataProviders\MotorhomeRangeDataProvider;

class MotorhomeRangeDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $motorhomeRange = factory(MotorhomeRange::class)->create();
        $page = $this->createPageForPageable($motorhomeRange, $site);

        $dataProvider = new MotorhomeRangeDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Motorhome Range', $motorhomeRange->overview),
            $dataProvider->generateSiteSearchData()
        );
    }
}