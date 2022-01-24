<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Page;
use App\Models\Panel;
use App\Models\Site;
use Carbon\Carbon;

class ViewablePanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_page_when_published_at_null_and_expired_at_null_and_live()
    {
        $panel = $this->createPanel([
            'live' => true,
            'published_at' => null,
            'expired_at' => null,
            'heading' => 'Panel Heading',
            'type' => Panel::TYPE_STANDARD,
        ]);
        $url = $this->url($panel->area->page);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($panel->heading);
    }

    public function test_renders_page_when_published_at_in_past_and_live()
    {
        $date = Carbon::yesterday();
        $panel = $this->createPanel([
            'live' => true,
            'published_at' => $date,
            'expired_at' => null,
            'heading' => 'Panel Heading',
            'type' => Panel::TYPE_STANDARD,
        ]);
        $url = $this->url($panel->area->page);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($panel->heading);
    }

    public function test_dont_see_area_when_live_is_false()
    {
        $panel = $this->createPanel([
            'live' => false,
            'published_at' => null,
            'expired_at' => null,
            'heading' => 'Panel Heading',
            'type' => Panel::TYPE_STANDARD,
        ]);
        $url = $this->url($panel->area->page);

        $response = $this->get($url);

        $response->assertDontSee($panel->heading);
    }

    public function test_dont_see_area_when_published_at_in_the_future()
    {
        $date = Carbon::tomorrow();
        $panel = $this->createPanel([
            'live' => true,
            'published_at' => $date,
            'expired_at' => null,
            'heading' => 'Panel Heading',
            'type' => Panel::TYPE_STANDARD,
        ]);
        $url = $this->url($panel->area->page);

        $response = $this->get($url);

        $response->assertDontSee($panel->heading);
    }

    public function test_dont_see_area_when_expired_at_in_the_past()
    {
        $date = Carbon::yesterday();
        $panel = $this->createPanel([
            'live' => true,
            'published_at' => null,
            'expired_at' => $date,
            'heading' => 'Panel Heading',
            'type' => Panel::TYPE_STANDARD,
        ]);
        $url = $this->url($panel->area->page);

        $response = $this->get($url);

        $response->assertDontSee($panel->heading);
    }

    private function createPanel($attributes = [])
    {
        $site = factory(Site::class)->create([
            'is_default' => true,
        ]);

        $page = factory(Page::class)->create([
            'site_id' => $site->id,
            'live' => true,
            'published_at' => null,
            'expired_at' => null,
        ]);

        $area = factory(Area::class)->create([
            'page_id' => $page->id,
        ]);

        $attributes['area_id'] = $area->id;

        return factory(Panel::class)->create($attributes);
    }

    private function url(Page $page)
    {
        return route('site', $page->slug);
    }
}
