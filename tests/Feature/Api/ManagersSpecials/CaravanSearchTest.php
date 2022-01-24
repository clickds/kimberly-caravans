<?php

namespace Tests\Feature\Api\ManagersSpecials;

use App\Models\CaravanStockItem;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CaravanSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Site::class)->states('default', 'has-stock')->create();
    }

    public function test_only_returns_managers_specials(): void
    {
        $managersSpecial = factory(CaravanStockItem::class)->create([
            'managers_special' => true,
        ]);
        $nonManagersSpecial = factory(CaravanStockItem::class)->create([
            'managers_special' => false,
        ]);

        $response = $this->makeRequest();

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $managersSpecial->id,
        ]);
        $response->assertJsonMissing([
            'id' => $nonManagersSpecial->id,
        ]);
    }

    public function test_filterable_by_dealer_id(): void
    {
        $this->markTestIncomplete();
    }

    private function makeRequest(): TestResponse
    {
        $url = route('api.caravan-stock-items.managers-specials.search');

        return $this->getJson($url);
    }
}
