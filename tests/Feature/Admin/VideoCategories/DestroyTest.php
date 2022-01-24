<?php

namespace Tests\Feature\Admin\VideoCategories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\VideoCategory;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $videoCategory = factory(VideoCategory::class)->create();

        $response = $this->submit($videoCategory);

        $response->assertRedirect(route('admin.video-categories.index'));
        $this->assertDatabaseMissing('video_categories', $videoCategory->getAttributes());
    }

    private function submit(VideoCategory $videoCategory)
    {
        $user = $this->createSuperUser();
        $url = $this->url($videoCategory);

        return $this->actingAs($user)->delete($url);
    }

    private function url(VideoCategory $videoCategory)
    {
        return route('admin.video-categories.destroy', $videoCategory);
    }
}
