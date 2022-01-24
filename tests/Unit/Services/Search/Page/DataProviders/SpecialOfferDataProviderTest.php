<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\SpecialOffer;
use App\Services\Search\Page\DataProviders\SpecialOfferDataProvider;

class SpecialOfferDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $specialOffer = factory(SpecialOffer::class)->create();
        $page = $this->createPageForPageable($specialOffer, $site);

        $dataProvider = new SpecialOfferDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Special Offer', strip_tags($specialOffer->content)),
            $dataProvider->generateSiteSearchData()
        );
    }
}