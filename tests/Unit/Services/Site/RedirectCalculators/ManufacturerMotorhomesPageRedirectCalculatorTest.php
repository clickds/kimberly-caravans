<?php

namespace Tests\Unit\Services\Site\RedirectCalculators;

use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use App\Services\Site\RedirectCalculators\ManufacturerMotorhomesPageRedirectCalculator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManufacturerMotorhomesPageRedirectCalculatorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_returns_redirect_to_motorhome_search_if_manufacturer_links_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $motorhomeSearchPage = $this->createMotorhomeSearchPage($site);
        $manufacturerMotorhomesPage = $this->createManufacturerMotorhomesPage($site, $manufacturer);

        $redirectCalculator = new ManufacturerMotorhomesPageRedirectCalculator($manufacturerMotorhomesPage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNotNull($redirect);

        $expectedRedirectUrl = sprintf(
            '%s?%s=%s&%s=%s',
            pageLink($motorhomeSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK
        );

        $this->assertEquals($expectedRedirectUrl, $redirect->getTargetUrl());
    }

    public function test_returns_null_if_motorhome_search_page_doesnt_exist()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => true]);
        $manufacturerMotorhomesPage = $this->createManufacturerMotorhomesPage($site, $manufacturer);

        $redirectCalculator = new ManufacturerMotorhomesPageRedirectCalculator($manufacturerMotorhomesPage);
        $redirect = $redirectCalculator->calculateRedirect();

        $this->assertNull($redirect);
    }

    public function test_returns_null_if_manufacturer_doesnt_link_directly_to_stock_search()
    {
        $site = factory(Site::class)->state('default')->create();
        $manufacturer = factory(Manufacturer::class)->create(['link_directly_to_stock_search' => false]);
        $manufacturerMotorhomesPage = $this->createManufacturerMotorhomesPage($site, $manufacturer);

        $redirectCalculator = new ManufacturerMotorhomesPageRedirectCalculator($manufacturerMotorhomesPage);
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

    private function createManufacturerMotorhomesPage(Site $site, Manufacturer $manufacturer): Page
    {
        return factory(Page::class)->create([
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
            'site_id' => $site->id,
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
        ]);
    }
}
