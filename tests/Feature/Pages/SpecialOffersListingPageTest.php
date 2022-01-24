<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SpecialOffer;
use App\Models\Page;
use App\Models\Site;
use App\Facades\SpecialOffer\ListingPage;

class SpecialOffersListingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $specialOffer = factory(SpecialOffer::class)->create();
        $specialOffer->sites()->attach($site);
        $specialOffer->pages()->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            'name' => 'Special Offer',
            'meta_title' => 'Special Offer',
            'live' => true,
        ]);
        $page = Page::create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFERS_LISTING,
            'name' => 'Special Offer',
            'meta_title' => 'Special Offer',
            'live' => true,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(ListingPage::class, $facade);
        $response->assertSee($specialOffer->name);
    }
}
