<?php

namespace Tests\Feature\Admin\Videos;

use App\Models\VideoCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Tests\TestCase;
use App\Models\Video;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $this->fakeStorage();

        $this->createCategory();

        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.videos.index'));

        $videoData = Arr::except($data, ['image', 'video_category_ids']);
        $this->assertDatabaseHas('videos', $videoData);

        foreach (Arr::get($data, 'video_category_ids') as $categoryId) {
            $this->assertDatabaseHas('video_video_category', [
                'video_category_id' => $categoryId,
            ]);
        }
        $video = Video::first();
        $this->assertFileExists($video->getFirstMedia('image')->getPath());
    }

    public function test_syncing_sites()
    {
        $this->fakeStorage();
        $this->createCategory();
        $site = $this->createSite();
        $data = $this->validFields();
        $data['site_ids'] = [$site->id];

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.videos.index'));
        $video = Video::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('pageable_site', [
            'pageable_type' => Video::class,
            'pageable_id' => $video->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseHas('pages', [
            'site_id' => $site->id,
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
        $this->createCategory();

        $data = $this->validFields([$requiredField => null]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($requiredField);
    }

    public function requiredFieldsProvider(): array
    {
        return [
            ['type'],
            ['title'],
            ['excerpt'],
            ['embed_code'],
            ['image'],
            ['published_at'],
        ];
    }

    public function test_it_requires_published_at_to_be_a_time()
    {
        $this->createCategory();

        $data = $this->validFields(['published_at' => 'abc']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('published_at');
    }

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'type' => $this->faker->randomElement(Video::VALID_TYPES),
            'video_category_ids' => [VideoCategory::first()->id],
            'title' => 'some name',
            'excerpt' => 'some excerpt',
            'embed_code' => 'some code',
            'image' => UploadedFile::fake()->image('video_image.jpg'),
            'published_at' => now(),
        ];

        return array_merge($defaults, $overrides);
    }

    private function url()
    {
        return route('admin.videos.store');
    }

    private function createCategory(): void
    {
        factory(VideoCategory::class)->create();
    }
}
