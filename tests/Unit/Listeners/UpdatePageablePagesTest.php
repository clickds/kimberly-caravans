<?php

namespace Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\PageableUpdated as Event;
use App\Listeners\UpdatePageablePages as Listener;
use App\Models\CaravanStockItem;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\Site;

class UpdatePageablePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_with_caravan_stock_item_pages_created_for_sites_that_have_stock()
    {
        $site = $this->createSite([
            'has_stock' => true,
        ]);
        $stockItem = factory(CaravanStockItem::class)->create();
        $listener = new Listener();
        $event = new Event($stockItem);

        $listener->handle($event);

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => "{$stockItem->manufacturerName()} {$stockItem->model} {$stockItem->unique_code}",
            "pageable_type" => CaravanStockItem::class,
            "pageable_id" => $stockItem->id,
            "template" => Page::TEMPLATE_CARAVAN_STOCK_ITEM,
        ]);
    }

    public function test_with_caravan_stock_item_pages_not_created_for_sites_that_do_not_have_stock()
    {
        $site = $this->createSite([
            'has_stock' => false,
        ]);
        $stockItem = factory(CaravanStockItem::class)->create();
        $listener = new Listener();
        $event = new Event($stockItem);

        $listener->handle($event);

        $this->assertDatabaseMissing('pages', [
            "site_id" => $site->id,
        ]);
    }

    public function test_with_motorhome_stock_item_pages_created_for_sites_that_have_stock()
    {
        $site = $this->createSite([
            'has_stock' => true,
        ]);
        $stockItem = factory(MotorhomeStockItem::class)->create();
        $listener = new Listener();
        $event = new Event($stockItem);

        $listener->handle($event);

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => "{$stockItem->manufacturerName()} {$stockItem->model} {$stockItem->unique_code}",
            "pageable_type" => MotorhomeStockItem::class,
            "pageable_id" => $stockItem->id,
            "template" => Page::TEMPLATE_MOTORHOME_STOCK_ITEM,
        ]);
    }

    public function test_with_motorhome_stock_item_pages_not_created_for_sites_that_do_not_have_stock()
    {
        $site = $this->createSite([
            'has_stock' => false,
        ]);
        $stockItem = factory(MotorhomeStockItem::class)->create();
        $listener = new Listener();
        $event = new Event($stockItem);

        $listener->handle($event);

        $this->assertDatabaseMissing('pages', [
            "site_id" => $site->id,
        ]);
    }
}
