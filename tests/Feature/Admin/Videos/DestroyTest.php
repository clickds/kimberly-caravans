<?php

namespace Tests\Feature\Admin\Videos;

use App\Models\VideoCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Video;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $video = $this->createVideo();

        $response = $this->submit($video);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseMissing('videos', [
            'id' => $video->id,
        ]);
    }

    public function test_removes_site_pages()
    {
        $site = $this->createSite();
        $video = $this->createVideo();
        $video->sites()->sync($site);
        $page = $this->createPageForPageable($video, $site);

        $response = $this->submit($video);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseMissing('videos', [
            'id' => $video->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Video::class,
            'pageable_id' => $video->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(Video $video)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($video);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Video $video)
    {
        return route('admin.videos.destroy', $video);
    }

    private function createVideo()
    {
        $category = factory(VideoCategory::class)->create();
        $video = factory(Video::class)->create();
        $video->videoCategories()->attach($category);

        return $video;
    }
}
