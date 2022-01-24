<?php

namespace Tests\Feature\Pages;

use App\Models\VideoCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Video;
use App\Models\Page;
use App\Models\Site;
use App\Facades\Video\ShowPage;

class VideoPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $parentPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_VIDEOS_LISTING,
        ]);
        $video = factory(Video::class)->create();
        $page = $video->pages()->create([
            'parent_id' => $parentPage->id,
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_VIDEO_SHOW,
            'name' => $video->title,
            'meta_title' => $video->title,
            'live' => true,
        ]);

        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(ShowPage::class, $facade);
        $response->assertSee($video->title);
    }
}
