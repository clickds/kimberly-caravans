<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SpecialOffer;
use App\Models\Page;
use App\Models\Site;
use App\Facades\SpecialOffer\CaravanShowPage;
use App\Facades\SpecialOffer\MotorhomeShowPage;

class SpecialOfferPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_caravan_page_rendered_correctly(): void
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $specialOffer = factory(SpecialOffer::class)->create();
        $page = $specialOffer->pages()->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW,
            'name' => $specialOffer->name,
            'meta_title' => $specialOffer->name,
            'live' => true,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(CaravanShowPage::class, $facade);
        $response->assertSee($specialOffer->name);
    }

    public function test_motorhome_page_rendered_correctly(): void
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $specialOffer = factory(SpecialOffer::class)->create();
        $page = $specialOffer->pages()->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW,
            'name' => $specialOffer->name,
            'meta_title' => $specialOffer->name,
            'live' => true,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(MotorhomeShowPage::class, $facade);
        $response->assertSee($specialOffer->name);
    }
}
