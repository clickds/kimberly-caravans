<?php

namespace Tests\Feature\Api\Stock;

use App\Http\Resources\Api\CaravanStockItemResource;
use App\Models\CaravanStockItem;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaravanSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Site::class)->states('default', 'has-stock')->create();

        factory(CaravanStockItem::class, 10)->create();
    }

    public function test_returns_all_caravans_when_no_filters_or_sorts_applied()
    {
        $count = CaravanStockItem::all()->count();

        $response = $this->getJson(route('api.caravan-stock-items.search'));

        $response->assertJsonCount($count, 'data');
    }

    public function test_applies_filters()
    {
        $newCaravans = CaravanStockItem::where('condition', CaravanStockItem::NEW_CONDITION)->get();

        $response = $this->getJson(route('api.caravan-stock-items.search') . '?filter[condition][eq]=New');

        $expectedData = CaravanStockItemResource::collection($newCaravans)->resolve();

        $response->assertJsonFragment(['data' => $expectedData]);
    }

    public function test_ignores_invalid_filters()
    {
        $allCaravans = CaravanStockItem::all();

        $response = $this->getJson(route('api.caravan-stock-items.search') . '?filter[someInvalidFilter][eq]=blah');

        $expectedData = CaravanStockItemResource::collection($allCaravans)->resolve();

        $response->assertJsonFragment(['data' => $expectedData]);
    }

    public function test_ignores_invalid_sorts()
    {
        $allCaravans = CaravanStockItem::all();

        $response = $this->getJson(route('api.caravan-stock-items.search') . '?sort[someInvalidSort]=asc');

        $expectedData = CaravanStockItemResource::collection($allCaravans)->resolve();

        $response->assertJsonFragment(['data' => $expectedData]);
    }
}
