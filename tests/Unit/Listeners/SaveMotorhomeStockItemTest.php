<?php

namespace Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\MotorhomeSaved;
use App\Listeners\SaveMotorhomeStockItem;
use App\Models\Berth;
use App\Models\Motorhome;
use App\Models\MotorhomeStockItem;
use App\Models\Seat;
use App\Models\Site;

class SaveMotorhomeStockItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_price_is_null(): void
    {
        $motorhome = factory(Motorhome::class)->create();
        $berth = factory(Berth::class)->create();
        $seat = factory(Seat::class)->create();
        $motorhome->berths()->attach($berth);
        $motorhome->seats()->attach($seat);
        $site = factory(Site::class)->state('has-stock')->create();
        $motorhome->sites()->attach([
            $site->id => [
                'price' => null,
            ],
        ]);
        $event = new MotorhomeSaved($motorhome);
        $listener = new SaveMotorhomeStockItem;

        $listener->handle($event);

        $this->assertDatabaseHas('motorhome_stock_items', [
            'motorhome_id' => $motorhome->id,
            'recommended_price' => null,
            'price' => null,
        ]);
    }

    public function test_when_new_motorhome_creates_stock_item(): void
    {
        $motorhome = factory(Motorhome::class)->create();
        $berth = factory(Berth::class)->create();
        $seat = factory(Seat::class)->create();
        $motorhome->berths()->attach($berth);
        $motorhome->seats()->attach($seat);
        $site = factory(Site::class)->state('has-stock')->create();
        $motorhome->sites()->attach([
            $site->id => [
                'price' => 500,
                'recommended_price' => 500,
            ],
        ]);
        $event = new MotorhomeSaved($motorhome);
        $listener = new SaveMotorhomeStockItem;

        $listener->handle($event);

        $this->assertDatabaseHas('motorhome_stock_items', [
            'motorhome_id' => $motorhome->id,
            'layout_id' => $motorhome->layout_id,
            'model' => $motorhome->name,
            'condition' => MotorhomeStockItem::NEW_CONDITION,
            'source' => MotorhomeStockItem::NEW_PRODUCT_SOURCE,
            'width' => $motorhome->width,
            'height' => $motorhome->height,
            'live' => $motorhome->live,
            'length' => $motorhome->length,
            'mro' => $motorhome->mro,
            'mtplm' => $motorhome->mtplm,
            'payload' => $motorhome->payload,
            'engine_size' => $motorhome->engine_size,
            'transmission' => $motorhome->transmission,
            'chassis_manufacturer' => $motorhome->chassis_manufacturer,
            'fuel' => $motorhome->fuel,
            'conversion' => $motorhome->conversion,
            'mileage' => 0,
            'year' => $motorhome->year,
            'recommended_price' => 500,
            'price' => 500,
            'description' => $motorhome->description,
            'number_of_owners' => 0,
        ]);
        $motorhomeStockItem = MotorhomeStockItem::latest()->first();
        $this->assertDatabaseHas('berth_motorhome_stock_item', [
            'berth_id' => $berth->id,
            'motorhome_stock_item_id' => $motorhomeStockItem->id,
        ]);
        $this->assertDatabaseHas('motorhome_stock_item_seat', [
            'seat_id' => $seat->id,
            'motorhome_stock_item_id' => $motorhomeStockItem->id,
        ]);
    }

    public function test_when_existing_motorhome_updates_stock_item()
    {
        $motorhome = factory(Motorhome::class)->create();
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'motorhome_id' => $motorhome->id,
            'layout_id' => $motorhome->layout_id,
            'manufacturer_id' => $motorhome->motorhomeRange->manufacturer_id,
        ]);
        $site = factory(Site::class)->state('has-stock')->create();
        $motorhome->sites()->attach([
            $site->id => [
                'price' => 500,
                'recommended_price' => 500,
            ],
        ]);
        $event = new MotorhomeSaved($motorhome);
        $listener = new SaveMotorhomeStockItem;

        $listener->handle($event);

        $this->assertDatabaseHas('motorhome_stock_items', [
            'id' => $stockItem->id,
            'motorhome_id' => $motorhome->id,
            'layout_id' => $motorhome->layout_id,
            'model' => $motorhome->name,
            'condition' => MotorhomeStockItem::NEW_CONDITION,
            'source' => MotorhomeStockItem::NEW_PRODUCT_SOURCE,
            'width' => $motorhome->width,
            'height' => $motorhome->height,
            'length' => $motorhome->length,
            'mro' => $motorhome->mro,
            'mtplm' => $motorhome->mtplm,
            'payload' => $motorhome->payload,
            'engine_size' => $motorhome->engine_size,
            'transmission' => $motorhome->transmission,
            'chassis_manufacturer' => $motorhome->chassis_manufacturer,
            'fuel' => $motorhome->fuel,
            'conversion' => $motorhome->conversion,
            'mileage' => 0,
            'year' => $motorhome->year,
            'recommended_price' => 500,
            'price' => 500,
            'description' => $motorhome->description,
            'number_of_owners' => 0,
        ]);
    }
}
