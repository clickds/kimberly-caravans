<?php

namespace Tests\Feature\Admin\VideoBanners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\VideoBanner;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_a_video_banner()
    {
        $videoBanner = factory(VideoBanner::class)->create();

        $response = $this->submit($videoBanner);

        $response->assertRedirect(route('admin.video-banners.index'));
        $this->assertDatabaseMissing('video_banners', $videoBanner->getAttributes());
    }

    private function submit(VideoBanner $videoBanner)
    {
        $user = $this->createSuperUser();
        $url = $this->url($videoBanner);

        return $this->actingAs($user)->delete($url);
    }

    private function url(VideoBanner $videoBanner)
    {
        return route('admin.video-banners.destroy', $videoBanner);
    }
}
