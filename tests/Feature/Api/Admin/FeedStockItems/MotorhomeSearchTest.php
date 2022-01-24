<?php

namespace Tests\Feature\Api\Admin\FeedStockItems;

use App\Models\MotorhomeStockItem;
use App\Models\Interfaces\StockItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class MotorhomeSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_gets_feed_items(): void
    {
        $feedItem = factory(MotorhomeStockItem::class)->create([
            'model' => 'abc',
            'source' => StockItem::FEED_SOURCE,
        ]);
        $newProductItem = factory(MotorhomeStockItem::class)->create([
            'model' => 'abc',
            'source' => StockItem::NEW_PRODUCT_SOURCE,
        ]);
        $data = [
            'keywords' => 'abc',
        ];

        $response = $this->submit($data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $feedItem->id,
        ]);
        $response->assertJsonMissing([
            'id' => $newProductItem->id,
        ]);
    }

    public function test_fetches_matching_items(): void
    {
        $matchingFeedItem = factory(MotorhomeStockItem::class)->create([
            'model' => 'abcdef',
            'source' => StockItem::FEED_SOURCE,
        ]);
        $nonMatchingFeedItem = factory(MotorhomeStockItem::class)->create([
            'model' => 'ghijkl',
            'source' => StockItem::FEED_SOURCE,
        ]);
        $data = [
            'keywords' => 'cde',
        ];

        $response = $this->submit($data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $matchingFeedItem->id,
        ]);
        $response->assertJsonMissing([
            'id' => $nonMatchingFeedItem->id,
        ]);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('api.admin.feed-stock-items.motorhome-search');

        return $this->actingAs($user)->json('GET', $url, $data);
    }
}
