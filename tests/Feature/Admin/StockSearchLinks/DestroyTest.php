<?php

namespace Tests\Feature\Admin\StockSearchLinks;

use App\Models\Page;
use App\Models\Site;
use App\Models\StockSearchLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function tests_successful(): void
    {
        $stockSearchLink = factory(StockSearchLink::class)->create();

        $response = $this->submit($stockSearchLink);

        $response->assertRedirect();
        $this->assertDatabaseMissing('stock_search_links', $stockSearchLink->getAttributes());
    }

    private function submit(StockSearchLink $stockSearchLink): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.stock-search-links.destroy', $stockSearchLink);

        return $this->actingAs($user)->delete($url);
    }
}
