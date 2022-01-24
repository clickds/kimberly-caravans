<?php

namespace Tests\Feature\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Page;
use App\Models\Site;
use Carbon\Carbon;

class ViewableAreaTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_page_when_published_at_null_and_expired_at_null_and_live()
    {
        $area = $this->createArea([
            'live' => true,
            'published_at' => null,
            'expired_at' => null,
            'heading' => 'Area Heading',
        ]);
        $url = $this->url($area->page);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($area->heading);
    }

    public function test_renders_page_when_published_at_in_past_and_live()
    {
        $date = Carbon::yesterday();
        $area = $this->createArea([
            'live' => true,
            'published_at' => $date,
            'expired_at' => null,
            'heading' => 'Area Heading',
        ]);
        $url = $this->url($area->page);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee($area->heading);
    }

    public function test_dont_see_area_when_live_is_false()
    {
        $area = $this->createArea([
            'live' => false,
            'published_at' => null,
            'expired_at' => null,
            'heading' => 'Area Heading',
        ]);
        $url = $this->url($area->page);

        $response = $this->get($url);

        $response->assertDontSee($area->heading);
    }

    public function test_dont_see_area_when_published_at_in_the_future()
    {
        $date = Carbon::tomorrow();
        $area = $this->createArea([
            'live' => true,
            'published_at' => $date,
            'expired_at' => null,
            'heading' => 'Area Heading',
        ]);
        $url = $this->url($area->page);

        $response = $this->get($url);

        $response->assertDontSee($area->heading);
    }

    public function test_dont_see_area_when_expired_at_in_the_past()
    {
        $date = Carbon::yesterday();
        $area = $this->createArea([
            'live' => true,
            'published_at' => null,
            'expired_at' => $date,
            'heading' => 'Area Heading',
        ]);
        $url = $this->url($area->page);

        $response = $this->get($url);

        $response->assertDontSee($area->heading);
    }

    private function createArea($attributes = [])
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

        $attributes['page_id'] = $page->id;

        return factory(Area::class)->create($attributes);
    }

    private function url(Page $page)
    {
        return route('site', $page->slug);
    }
}
