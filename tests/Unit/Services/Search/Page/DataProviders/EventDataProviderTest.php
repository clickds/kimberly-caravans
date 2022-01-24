<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Event;
use App\Services\Search\Page\DataProviders\EventDataProvider;

class EventDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $event = factory(Event::class)->create();
        $page = $this->createPageForPageable($event, $site);

        $dataProvider = new EventDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Event', $event->summary),
            $dataProvider->generateSiteSearchData()
        );
    }
}