<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Dealer;
use App\Models\DealerLocation;
use App\Presenters\DealerPresenter;
use App\Services\Search\Page\DataProviders\DealerDataProvider;

class DealerDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $dealer = factory(Dealer::class)->create();
        $dealer->locations()->save(factory(DealerLocation::class)->make());

        $page = $this->createPageForPageable($dealer, $site);
        $presenter = (new DealerPresenter())->setWrappedObject($dealer);

        $dataProvider = new DealerDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Dealership', $presenter->getFormattedAddress()),
            $dataProvider->generateSiteSearchData()
        );
    }
}