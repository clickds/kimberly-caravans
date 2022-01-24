<?php

namespace Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\CaravanSaved;
use App\Listeners\SaveCaravanStockItem;
use App\Models\Berth;
use App\Models\Caravan;
use App\Models\CaravanStockItem;
use App\Models\Site;

class SaveCaravanStockItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_price_is_null(): void
    {
        $caravan = factory(Caravan::class)->create();
        $berth = factory(Berth::class)->create();
        $caravan->berths()->attach($berth);
        $site = factory(Site::class)->state('has-stock')->create();
        $caravan->sites()->attach([
            $site->id => [
                'price' => null,
                'recommended_price' => null,
            ],
        ]);
        $event = new CaravanSaved($caravan);
        $listener = new SaveCaravanStockItem;

        $listener->handle($event);

        $this->assertDatabaseHas('caravan_stock_items', [
            'caravan_id' => $caravan->id,
            'recommended_price' => null,
            'price' => null,
        ]);
    }

    public function test_when_new_caravan_creates_stock_item()
    {
        $caravan = factory(Caravan::class)->create();
        $berth = factory(Berth::class)->create();
        $caravan->berths()->attach($berth);
        $site = factory(Site::class)->state('has-stock')->create();
        $caravan->sites()->attach([
            $site->id => [
                'price' => 500,
                'recommended_price' => 500,
            ],
        ]);
        $event = new CaravanSaved($caravan);
        $listener = new SaveCaravanStockItem;

        $listener->handle($event);

        $this->assertDatabaseHas('caravan_stock_items', [
            'caravan_id' => $caravan->id,
            'layout_id' => $caravan->layout_id,
            'model' => $caravan->name,
            'condition' => CaravanStockItem::NEW_CONDITION,
            'source' => CaravanStockItem::NEW_PRODUCT_SOURCE,
            'axles' => $caravan->axles,
            'width' => $caravan->width,
            'height' => $caravan->height,
            'length' => $caravan->length,
            'live' => $caravan->live,
            'mro' => $caravan->mro,
            'mtplm' => $caravan->mtplm,
            'payload' => $caravan->payload,
            'year' => $caravan->year,
            'recommended_price' => 500,
            'price' => 500,
            'description' => $caravan->description,
            'number_of_owners' => 0,
        ]);
        $caravanStockItem = CaravanStockItem::latest()->first();
        $this->assertDatabaseHas('berth_caravan_stock_item', [
            'berth_id' => $berth->id,
            'caravan_stock_item_id' => $caravanStockItem->id,
        ]);
    }

    public function test_when_existing_caravan_updates_stock_item()
    {
        $caravan = factory(Caravan::class)->create();
        $stockItem = factory(CaravanStockItem::class)->create([
            'caravan_id' => $caravan->id,
            'layout_id' => $caravan->layout_id,
            'manufacturer_id' => $caravan->caravanRange->manufacturer_id,
        ]);
        $site = factory(Site::class)->state('has-stock')->create();
        $caravan->sites()->attach([
            $site->id => [
                'price' => 500,
                'recommended_price' => 500,
            ],
        ]);
        $event = new CaravanSaved($caravan);
        $listener = new SaveCaravanStockItem;

        $listener->handle($event);

        $this->assertDatabaseHas('caravan_stock_items', [
            'id' => $stockItem->id,
            'caravan_id' => $caravan->id,
            'layout_id' => $caravan->layout_id,
            'model' => $caravan->name,
            'condition' => CaravanStockItem::NEW_CONDITION,
            'source' => CaravanStockItem::NEW_PRODUCT_SOURCE,
            'width' => $caravan->width,
            'height' => $caravan->height,
            'length' => $caravan->length,
            'mro' => $caravan->mro,
            'mtplm' => $caravan->mtplm,
            'payload' => $caravan->payload,
            'year' => $caravan->year,
            'recommended_price' => 500,
            'price' => 500,
            'description' => $caravan->description,
            'number_of_owners' => 0,
        ]);
    }
}
