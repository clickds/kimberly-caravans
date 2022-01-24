<?php

namespace Tests\Feature\Admin\Dealers;

use App\Models\DealerLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Dealer;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $dealer = $this->createDealer();

        $dealerId = $dealer->id;
        $dealerLocationId = $dealer->locations()->first()->id;

        $response = $this->submit($dealer);

        $response->assertRedirect(route('admin.dealers.index'));

        $this->assertDatabaseMissing('dealers', ['id' => $dealerId]);

        $this->assertDatabaseMissing('dealer_locations', ['id' => $dealerLocationId]);
    }

    private function submit(Dealer $dealer)
    {
        $admin = $this->createSuperUser();

        $url = $this->url($dealer);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Dealer $dealer): string
    {
        return route('admin.dealers.destroy', $dealer);
    }

    private function createDealer(): Dealer
    {
        $dealer = factory(Dealer::class)->create();

        $dealer->locations()->save(factory(DealerLocation::class)->make());

        return $dealer;
    }
}
