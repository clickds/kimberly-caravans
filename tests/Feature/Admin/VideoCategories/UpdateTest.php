<?php

namespace Tests\Feature\Admin\VideoCategories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\VideoCategory;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_required()
    {
        $videoCategory = factory(VideoCategory::class)->create();
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($videoCategory, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_must_be_unique()
    {
        $videoCategory = factory(VideoCategory::class)->create();
        $otherVideoCategory = factory(VideoCategory::class)->create();
        $data = $this->validFields([
            'name' => $otherVideoCategory->name,
        ]);

        $response = $this->submit($videoCategory, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successful()
    {
        $videoCategory = factory(VideoCategory::class)->create();
        $data = $this->validFields();

        $response = $this->submit($videoCategory, $data);

        $response->assertRedirect(route('admin.video-categories.index'));
        $this->assertDatabaseHas('video_categories', $data);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'position' => 1,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(VideoCategory $videoCategory, array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url($videoCategory);

        return $this->actingAs($user)->put($url, $data);
    }

    private function url(VideoCategory $videoCategory)
    {
        return route('admin.video-categories.update', $videoCategory);
    }
}
