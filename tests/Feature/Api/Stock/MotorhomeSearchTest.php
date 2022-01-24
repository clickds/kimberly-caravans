<?php

namespace Tests\Feature\Api\Stock;

use App\Http\Resources\Api\MotorhomeStockItemResource;
use App\Models\MotorhomeStockItem;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MotorhomeSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Site::class)->states('default', 'has-stock')->create();

        factory(MotorhomeStockItem::class, 10)->create();
    }

    public function test_returns_all_motorhomes_when_no_filters_or_sorts_applied()
    {
        $count = MotorhomeStockItem::all()->count();

        $response = $this->getJson(route('api.motorhome-stock-items.search'));

        $response->assertJsonCount($count, 'data');
    }

    public function test_applies_filters()
    {
        $newMotorhomes = MotorhomeStockItem::where('condition', MotorhomeStockItem::NEW_CONDITION)->get();

        $response = $this->getJson(route('api.motorhome-stock-items.search') . '?filter[condition][eq]=New');

        $expectedData = MotorhomeStockItemResource::collection($newMotorhomes)->resolve();

        $response->assertJsonFragment(['data' => $expectedData]);
    }

    public function test_ignores_invalid_filters()
    {
        $allMotorhomes = MotorhomeStockItem::all();

        $response = $this->getJson(route('api.motorhome-stock-items.search') . '?filter[someInvalidFilter][eq]=Blah');

        $expectedData = MotorhomeStockItemResource::collection($allMotorhomes)->resolve();

        $response->assertJsonFragment(['data' => $expectedData]);
    }

    public function test_ignores_invalid_sorts()
    {
        $allMotorhomes = MotorhomeStockItem::all();

        $response = $this->getJson(route('api.motorhome-stock-items.search') . '?sort[someInvalidSort]=asc');

        $expectedData = MotorhomeStockItemResource::collection($allMotorhomes)->resolve();

        $response->assertJsonFragment(['data' => $expectedData]);
    }
}
