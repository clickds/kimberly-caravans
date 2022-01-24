<?php

namespace Tests\Feature\Api\Admin\Videos;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $videos = factory(Video::class, 10)->create();

        $response = $this->submit();

        $response->assertStatus(200);
        foreach ($videos as $video) {
            $response->assertJsonFragment([
                'id' => $video->id,
                'title' => $video->title,
            ]);
        }
    }

    private function submit()
    {
        $user = $this->createSuperUser();
        $url = $this->url();
        return $this->actingAs($user)->get($url);
    }

    private function url()
    {
        return route('api.admin.videos.index');
    }
}
