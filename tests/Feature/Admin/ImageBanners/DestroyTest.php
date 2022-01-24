<?php

namespace Tests\Feature\Admin\ImageBanners;

use App\Models\ImageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_destroys_image_banner(): void
    {
        $imageBanner = $this->createImageBanner();

        $response = $this->submit($imageBanner);

        $response->assertRedirect(route('admin.image-banners.index'));
        $this->assertDatabaseMissing('image_banners', $imageBanner->getAttributes());
    }

    private function submit(ImageBanner $imageBanner): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.image-banners.destroy', $imageBanner);

        return $this->actingAs($user)->delete($url);
    }

    private function createImageBanner(array $attributes = []): ImageBanner
    {
        return factory(ImageBanner::class)->create($attributes);
    }
}
