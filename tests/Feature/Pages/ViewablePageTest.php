<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Page;
use App\Models\Site;
use Carbon\Carbon;

class ViewablePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_page_when_published_at_null_and_expired_at_null_and_live()
    {
        $page = $this->createPage([
            'live' => true,
            'published_at' => null,
            'expired_at' => null,
        ]);
        $url = $this->url($page);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($page->meta_title);
    }

    public function test_renders_page_when_published_at_in_past_and_live()
    {
        $date = Carbon::yesterday();
        $page = $this->createPage([
            'live' => true,
            'published_at' => $date,
            'expired_at' => null,
        ]);
        $url = $this->url($page);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($page->meta_title);
    }

    public function test_404s_when_live_is_false()
    {
        $page = $this->createPage([
            'live' => false,
            'published_at' => null,
            'expired_at' => null,
        ]);
        $url = $this->url($page);

        $response = $this->get($url);

        $response->assertStatus(404);
    }

    public function test_404s_when_published_at_in_the_future()
    {
        $date = Carbon::tomorrow();
        $page = $this->createPage([
            'live' => true,
            'published_at' => $date,
            'expired_at' => null,
        ]);
        $url = $this->url($page);

        $response = $this->get($url);

        $response->assertStatus(404);
    }

    public function test_404s_when_expired_at_in_the_past()
    {
        $date = Carbon::yesterday();
        $page = $this->createPage([
            'live' => true,
            'published_at' => null,
            'expired_at' => $date,
        ]);
        $url = $this->url($page);

        $response = $this->get($url);

        $response->assertStatus(404);
    }

    private function createPage($attributes = [])
    {
        $site = factory(Site::class)->create([
            'is_default' => true,
        ]);
        $attributes['site_id'] = $site->id;

        return factory(Page::class)->create($attributes);
    }

    private function url(Page $page)
    {
        return route('site', $page->slug);
    }
}
