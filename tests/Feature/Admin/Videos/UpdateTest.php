<?php

namespace Tests\Feature\Admin\Videos;

use App\Models\VideoCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Video;
use Illuminate\Support\Arr;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $video = $this->createVideo();

        $data = $this->validFields();

        $response = $this->submit($video, $data);

        $response->assertRedirect(route('admin.videos.index'));

        $videoData = Arr::except($data, ['video_category_ids']);
        $this->assertDatabaseHas('videos', $videoData);
        foreach (Arr::get($data, 'video_category_ids') as $categoryId) {
            $this->assertDatabaseHas('video_video_category', [
                'video_id' => $video->id,
                'video_category_id' => $categoryId,
            ]);
        }
    }

    public function test_syncing_sites()
    {
        $old_site = $this->createSite();
        $video = $this->createVideo();
        $video->sites()->sync($old_site);
        $page = $this->createPageForPageable($video, $old_site);

        $new_site = $this->createSite();
        $data = $this->validFields();
        $data['site_ids'] = [$new_site->id];

        $response = $this->submit($video, $data);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Video::class,
            'pageable_id' => $video->id,
            'site_id' => $new_site->id,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $new_site->id,
            'pageable_type' => Video::class,
            'pageable_id' => $video->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Video::class,
            'pageable_id' => $video->id,
            'site_id' => $old_site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'site_id' => $old_site->id,
            'pageable_type' => Video::class,
            'pageable_id' => $video->id,
        ]);
    }

    /**
     * @dataProvider requiredFieldsProvider
     * @param $requiredField string
     */
    public function test_required_validation(string $requiredField): void
    {
        $video = $this->createVideo();

        $data = $this->validFields([$requiredField => null]);

        $response = $this->submit($video, $data);

        $response->assertSessionHasErrors($requiredField);
    }

    public function requiredFieldsProvider(): array
    {
        return [
            ['type'],
            ['title'],
            ['excerpt'],
            ['embed_code'],
            ['published_at'],
        ];
    }

    public function test_it_requires_published_at_to_be_a_time()
    {
        $video = $this->createVideo();

        $data = $this->validFields(['published_at' => 'abc']);

        $response = $this->submit($video, $data);

        $response->assertSessionHasErrors('published_at');
    }

    private function submit(Video $video, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url($video);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $defaults = [
            'title' => 'some title',
            'type' => $this->faker->randomElement(Video::VALID_TYPES),
            'video_category_ids' => [VideoCategory::first()->id],
            'excerpt' => 'some excerpt',
            'embed_code' => 'some code',
            'published_at' => now(),
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Video $video): string
    {
        return route('admin.videos.update', $video);
    }

    private function createVideo(): Video
    {
        $category = factory(VideoCategory::class)->create();
        $video = factory(Video::class)->create();
        $video->videoCategories()->attach($category);

        return $video;
    }
}
