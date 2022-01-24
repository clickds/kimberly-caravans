<?php

namespace Tests\Unit\Presenters\PanelType;

use App\Models\Page;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\PanelType\StockItemCategoryTabsPresenter as PanelPresenter;
use App\Models\Panel;
use App\Models\Site;
use App\QueryBuilders\AbstractStockItemQueryBuilder;

class StockItemCategoryTabsPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_arrivals_filter(): void
    {
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals(AbstractStockItemQueryBuilder::STATUS_NEW_ARRIVALS, $presenter->newArrivalsFilter());
    }

    public function test_new_stock_filter(): void
    {
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals(AbstractStockItemQueryBuilder::STATUS_NEW_STOCK, $presenter->newStockFilter());
    }

    public function test_used_stock_filter(): void
    {
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertEquals(AbstractStockItemQueryBuilder::STATUS_USED_STOCK, $presenter->usedStockFilter());
    }

    public function test_get_caravan_search_page_when_page_does_not_exist(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $pagePresenter = $presenter->getCaravanSearchPage();

        $this->assertNull($pagePresenter);
    }

    public function test_get_caravan_search_page_when_page_exists(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_CARAVAN_SEARCH,
        ]);
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $pagePresenter = $presenter->getCaravanSearchPage();

        $this->assertEquals(Page::TEMPLATE_CARAVAN_SEARCH, $pagePresenter->template);
        $this->assertEquals($site->id, $pagePresenter->site_id);
    }

    public function test_get_motorhome_search_page_when_page_does_not_exist(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $pagePresenter = $presenter->getMotorhomeSearchPage();

        $this->assertNull($pagePresenter);
    }

    public function test_get_motorhome_search_page_when_page_exists(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_MOTORHOME_SEARCH,
        ]);
        $panel = factory(Panel::class)->make();
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $pagePresenter = $presenter->getMotorhomeSearchPage();

        $this->assertEquals(Page::TEMPLATE_MOTORHOME_SEARCH, $pagePresenter->template);
        $this->assertEquals($site->id, $pagePresenter->site_id);
    }
}
