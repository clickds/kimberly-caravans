<?php

namespace Tests\Unit\Services\Site\RedirectCalculators;

use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use App\Services\Site\RedirectCalculators\ManufacturerCaravansPageRedirectCalculator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManufacturerCaravansPageRedirectCalculatorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_returns_redirect_to_caravan_search_if_manufacturer_links_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $caravanSearchPage = $this->createCaravanSearchPage($site);
        $manufacturerCaravansPage = $this->createManufacturerCaravansPage($site, $manufacturer);

        $redirectCalculator = new ManufacturerCaravansPageRedirectCalculator($manufacturerCaravansPage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNotNull($redirect);

        $expectedRedirectUrl = sprintf(
            '%s?%s=%s&%s=%s',
            pageLink($caravanSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK
        );

        $this->assertEquals($expectedRedirectUrl, $redirect->getTargetUrl());
    }

    public function test_returns_null_if_caravan_search_page_doesnt_exist()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $manufacturerCaravansPage = $this->createManufacturerCaravansPage($site, $manufacturer);

        $redirectCalculator = new ManufacturerCaravansPageRedirectCalculator($manufacturerCaravansPage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNull($redirect);
    }

    public function test_returns_null_if_manufacturer_doesnt_link_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => false]);
        $manufacturerCaravansPage = $this->createManufacturerCaravansPage($site, $manufacturer);

        $redirectCalculator = new ManufacturerCaravansPageRedirectCalculator($manufacturerCaravansPage);
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

    private function createManufacturerCaravansPage(Site $site, Manufacturer $manufacturer): Page
    {
        return factory(Page::class)->create([
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
            'site_id' => $site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
        ]);
    }
}
