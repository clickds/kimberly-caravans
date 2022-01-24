<?php

namespace Tests\Feature\Admin\VideoBanners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\VideoBanner;
use Illuminate\Support\Arr;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_fields(string $inputName): void
    {
        $data = $this->validFields([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['webm'],
            ['mp4'],
            ['live'],
        ];
    }

    public function test_name_is_unique()
    {
        $videoBanner = factory(VideoBanner::class)->create();
        $data = $this->validFields([
            'name' => $videoBanner->name,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_webm_is_a_webm()
    {
        $data = $this->validFields([
            'webm' => UploadedFile::fake()->create('video.mp4'),
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('webm');
    }

    public function test_mp4_is_a_mp4()
    {
        $data = $this->validFields([
            'mp4' => UploadedFile::fake()->create('video.webm'),
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('mp4');
    }

    public function test_creates_a_video_banner()
    {
        $this->fakeStorage();

        $data = $this->validFields();

        $this->submit($data);

        $videoBanner = VideoBanner::first();

        $this->assertDatabaseHas('video_banners', Arr::only($data, ['name', 'live', 'published_at', 'expired_at']));

        $this->assertDatabaseHas('media', [
            'model_id' => $videoBanner->id,
            'model_type' => VideoBanner::class,
            'collection_name' => 'mp4',
        ]);

        $this->assertDatabaseHas('media', [
            'collection_name' => 'webm',
            'model_id' => $videoBanner->id,
            'model_type' => VideoBanner::class,
        ]);
    }

    private function validFields(array $overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'mp4' => UploadedFile::fake()->create('video.mp4', 5000),
            'webm' => UploadedFile::fake()->create('video.webm', 5000),
            'published_at' => null,
            'expired_at' => null,
            'live' => true,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data)
    {
        $user = $this->createSuperUser();
        $url = route('admin.video-banners.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
