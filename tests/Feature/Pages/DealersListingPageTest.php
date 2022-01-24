<?php

namespace Tests\Feature\Pages;

use App\Models\DealerLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Dealer;
use App\Models\Page;
use App\Models\Site;
use App\Facades\Dealer\ListingPage;

class DealersListingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_DEALERS_LISTING,
        ]);


        $dealer = factory(Dealer::class)->create([
            'site_id' => $site->id,
        ]);

        $dealer->locations()->save(
            factory(DealerLocation::class)->make()
        );

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(ListingPage::class, $facade);
        $response->assertSee($dealer->name);
    }
}
