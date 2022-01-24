<?php

namespace Tests\Feature\Api\SpecialOffer;

use App\Models\MotorhomeStockItem;
use App\Models\Site;
use App\Models\SpecialOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class MotorhomeSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Site::class)->states('default', 'has-stock')->create();
    }

    public function test_only_returns_stock_on_special_offer(): void
    {
        $offer = factory(SpecialOffer::class)->create();
        $offerStockItem = factory(MotorhomeStockItem::class)->create();
        $nonOfferStockItem = factory(MotorhomeStockItem::class)->create();
        $offer->motorhomeStockItems()->attach($offerStockItem);

        $response = $this->makeRequest($offer);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $offerStockItem->id,
        ]);
        $response->assertJsonMissing([
            'id' => $nonOfferStockItem->id,
        ]);
    }

    private function makeRequest(SpecialOffer $specialOffer): TestResponse
    {
        $url = route('api.motorhome-stock-items.special-offer.search', $specialOffer);

        return $this->getJson($url);
    }
}
