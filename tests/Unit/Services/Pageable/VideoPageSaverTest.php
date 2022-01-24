<?php

namespace Tests\Unit\Services\Pageable;

use App\Models\VideoCategory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Video;
use App\Models\Page;
use App\Services\Pageable\VideoPageSaver;

class VideoPageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_video_pageable_creates_event_show_page()
    {
        $site = $this->createSite();
        $video = factory(Video::class)->create();
        $saver = new VideoPageSaver($video, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $video->title,
            "pageable_type" => Video::class,
            "pageable_id" => $video->id,
            "template" => Page::TEMPLATE_VIDEO_SHOW,
        ]);
    }
}
