<?php

namespace Tests\Unit\Services\Site\RedirectCalculators;

use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use App\Services\Site\RedirectCalculators\CaravanRangePageRedirectCalculator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaravanRangePageRedirectCalculatorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_returns_redirect_to_caravan_search_if_manufacturer_links_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $range = factory(CaravanRange::class)->create(['manufacturer_id' => $manufacturer->id]);
        $caravanSearchPage = $this->createCaravanSearchPage($site);
        $caravanRangePage = $this->createCaravanRangePage($site, $range);

        $redirectCalculator = new CaravanRangePageRedirectCalculator($caravanRangePage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNotNull($redirect);

        $expectedRedirectUrl = sprintf(
            '%s?%s=%s&%s=%s&%s=%s',
            pageLink($caravanSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK,
            'search-term',
            $range->name
        );

        $this->assertEquals($expectedRedirectUrl, $redirect->getTargetUrl());
    }

    public function test_returns_null_if_caravan_search_page_doesnt_exist()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $range = factory(CaravanRange::class)->create(['manufacturer_id' => $manufacturer->id]);
        $caravanRangePage = $this->createCaravanRangePage($site, $range);

        $redirectCalculator = new CaravanRangePageRedirectCalculator($caravanRangePage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNull($redirect);
    }

    public function test_returns_null_if_manufacturer_doesnt_link_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => false]);
        $range = factory(CaravanRange::class)->create(['manufacturer_id' => $manufacturer->id]);
        $caravanRangePage = $this->createCaravanRangePage($site, $range);

        $redirectCalculator = new CaravanRangePageRedirectCalculator($caravanRangePage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNull($redirect);
    }

    private function createCaravanSearchPage(Site $site): Page
    {
        return factory(Page::class)->create([
            'template' => Page::TEMPLATE_CARAVAN_SEARCH,
            'site_id' => $site->id,
        ]);
    }

    private function createCaravanRangePage(Site $site, CaravanRange $range): Page
    {
        return factory(Page::class)->create([
            'template' => Page::TEMPLATE_CARAVAN_RANGE,
            'site_id' => $site->id,
            'pageable_type' => CaravanRange::class,
            'pageable_id' => $range->id,
        ]);
    }
}
