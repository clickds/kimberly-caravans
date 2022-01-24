<?php

namespace Tests\Feature\Admin\SpecialOffers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SpecialOffer;

class DestroyTest extends TestCase
{
    use RefreshDatabase;
    public function test_successful()
    {
        $specialOffer = $this->createSpecialOffer();

        $response = $this->submit($specialOffer);

        $response->assertRedirect(route('admin.special-offers.index'));
        $this->assertDatabaseMissing('special_offers', [
            'id' => $specialOffer->id,
        ]);
    }

    public function test_removes_site_page()
    {
        $site = $this->createSite();
        $specialOffer = $this->createSpecialOffer([
            'site_id' => $site->id,
        ]);
        $page = $this->createPageForPageable($specialOffer, $site);

        $response = $this->submit($specialOffer);

        $response->assertRedirect(route('admin.special-offers.index'));
        $this->assertDatabaseMissing('special_offers', [
            'id' => $specialOffer->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => SpecialOffer::class,
            'pageable_id' => $specialOffer->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(SpecialOffer $specialOffer)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($specialOffer);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(SpecialOffer $specialOffer)
    {
        return route('admin.special-offers.destroy', $specialOffer);
    }

    private function createSpecialOffer()
    {
        return factory(SpecialOffer::class)->create();
    }
}
