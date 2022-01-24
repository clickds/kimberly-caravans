<?php

namespace Tests\Feature\Pages;

use App\Facades\StockItem\MotorhomePage;
use App\Models\MotorhomeStockItem;
use App\Models\MotorhomeStockItemFeature;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MotorhomeStockItemPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $stockItem = factory(MotorhomeStockItem::class)->create();
        $feature = factory(MotorhomeStockItemFeature::class)->create([
            'motorhome_stock_item_id' => $stockItem->id,
        ]);
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_MOTORHOME_STOCK_ITEM,
            'pageable_type' => MotorhomeStockItem::class,
            'pageable_id' => $stockItem->id,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(MotorhomePage::class, $facade);

        $response->assertSee($stockItem->unique_code);
        $response->assertSee($stockItem->manufacturerName());
        $response->assertSee($stockItem->model);
        $response->assertSee(number_format($stockItem->price));
    }
}
