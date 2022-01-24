<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Page;
use App\Models\Site;
use App\Models\UsefulLink;
use App\Models\UsefulLinkCategory;
use App\Facades\UsefulLink\ListingPage;

class UsefulLinkListingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_USEFUL_LINK_LISTING,
        ]);


        $usefulLinkCategory = factory(UsefulLinkCategory::class)->create();
        $otherLinkCategory = factory(UsefulLinkCategory::class)->create();
        $usefulLink = factory(UsefulLink::class)->create([
            'useful_link_category_id' => $usefulLinkCategory->id,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(ListingPage::class, $facade);
        $response->assertSee($usefulLinkCategory->name);
        $response->assertDontSee($otherLinkCategory->name);
    }
}
