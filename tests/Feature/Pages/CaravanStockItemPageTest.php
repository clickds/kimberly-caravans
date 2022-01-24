<?php

namespace Tests\Feature\Pages;

use App\Facades\StockItem\CaravanPage;
use App\Models\CaravanStockItem;
use App\Models\CaravanStockItemFeature;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CaravanStockItemPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $stockItem = factory(CaravanStockItem::class)->create();
        $feature = factory(CaravanStockItemFeature::class)->create([
            'caravan_stock_item_id' => $stockItem->id,
        ]);
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_CARAVAN_STOCK_ITEM,
            'pageable_type' => CaravanStockItem::class,
            'pageable_id' => $stockItem->id,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(CaravanPage::class, $facade);

        $response->assertSee($stockItem->unique_code);
        $response->assertSee($stockItem->manufacturerName());
        $response->assertSee($stockItem->model);
        $response->assertSee(number_format($stockItem->price));
    }
}
