<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use App\Models\Area;
use App\Models\Page;
use App\Models\Site;

class RendersCorrectPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_the_correct_page_for_url()
    {
        $site = factory(Site::class)->create([
            'is_default' => false,
        ]);
        $page = factory(Page::class)
            ->create([
                'site_id' => $site->id,
            ]);
        $url = "http://" . $site->subdomain . ".marquisleisure.co.uk/" . $page->slug;

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($page->meta_title);
    }

    public function test_renders_correct_child_page_for_requested_url()
    {
        $site = factory(Site::class)->create([
            'is_default' => false,
        ]);
        $page = factory(Page::class)
            ->create([
                'site_id' => $site->id,
            ]);
        $childPage = factory(Page::class)
            ->create([
                'site_id' => $site->id,
                'parent_id' => $page->id,
            ]);
        $url = "http://" . $site->subdomain . ".marquisleisure.co.uk/" . $page->slug . "/" . $childPage->slug;

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($childPage->meta_title);
    }
}
