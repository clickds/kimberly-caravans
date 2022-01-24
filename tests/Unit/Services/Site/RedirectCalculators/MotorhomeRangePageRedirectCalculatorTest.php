<?php

namespace Tests\Unit\Services\Site\RedirectCalculators;

use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Site;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use App\Services\Site\RedirectCalculators\MotorhomeRangePageRedirectCalculator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MotorhomeRangePageRedirectCalculatorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_returns_redirect_to_motorhome_search_if_manufacturer_links_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $range = factory(MotorhomeRange::class)->create(['manufacturer_id' => $manufacturer->id]);
        $motorhomeSearchPage = $this->createMotorhomeSearchPage($site);
        $motorhomeRangePage = $this->createMotorhomeRangePage($site, $range);

        $redirectCalculator = new MotorhomeRangePageRedirectCalculator($motorhomeRangePage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNotNull($redirect);

        $expectedRedirectUrl = sprintf(
            '%s?%s=%s&%s=%s&%s=%s',
            pageLink($motorhomeSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK,
            'search-term',
            $range->name
        );

        $this->assertEquals($expectedRedirectUrl, $redirect->getTargetUrl());
    }

    public function test_returns_null_if_motorhome_search_page_doesnt_exist()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $range = factory(MotorhomeRange::class)->create(['manufacturer_id' => $manufacturer->id]);
        $motorhomeRangePage = $this->createMotorhomeRangePage($site, $range);

        $redirectCalculator = new MotorhomeRangePageRedirectCalculator($motorhomeRangePage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNull($redirect);
    }

    public function test_returns_null_if_manufacturer_doesnt_link_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => false]);
        $range = factory(MotorhomeRange::class)->create(['manufacturer_id' => $manufacturer->id]);
        $motorhomeRangePage = $this->createMotorhomeRangePage($site, $range);

        $redirectCalculator = new MotorhomeRangePageRedirectCalculator($motorhomeRangePage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNull($redirect);
    }

    private function createMotorhomeSearchPage(Site $site): Page
    {
        return factory(Page::class)->create([
            'template' => Page::TEMPLATE_MOTORHOME_SEARCH,
            'site_id' => $site->id,
        ]);
    }

    private function createMotorhomeRangePage(Site $site, MotorhomeRange $range): Page
    {
        return factory(Page::class)->create([
            'template' => Page::TEMPLATE_MOTORHOME_RANGE,
            'site_id' => $site->id,
            'pageable_type' => MotorhomeRange::class,
            'pageable_id' => $range->id,
        ]);
    }
}
