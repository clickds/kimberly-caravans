<?php

namespace Tests\Feature\Admin\VideoCategories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\VideoCategory;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_required()
    {
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_must_be_unique()
    {
        $videoCategory = factory(VideoCategory::class)->create();
        $data = $this->validFields([
            'name' => $videoCategory->name,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successful()
    {
        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.video-categories.index'));
        $this->assertDatabaseHas('video_categories', $data);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'position' => 2,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data)
    {
        $user = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($user)->post($url, $data);
    }

    private function url()
    {
        return route('admin.video-categories.store');
    }
}
