<?php

namespace Tests\Feature\Pages;

use App\Models\DealerLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Page;
use App\Models\Site;
use App\Facades\NewsAndInfoLanderPage;
use App\Models\Cta;

class NewsAndInfoLanderPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_NEWS_AND_INFO_LANDER,
        ]);
        $cta = factory(Cta::class)->create([
            'site_id' => $site->id,
        ]);

        $response = $this->get($page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(NewsAndInfoLanderPage::class, $facade);
        $response->assertSee($cta->name);
    }
}
