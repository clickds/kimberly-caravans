<?php

namespace Tests\Feature\Admin\CaravanRange\InteriorGalleryImages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\CaravanRange;
use App\Models\Site;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $caravanRange = $this->createCaravanRange();
        $image = $this->createImage($caravanRange);

        $response = $this->submit($caravanRange, $image);

        $response->assertRedirect(route('admin.caravan-ranges.interior-gallery-images.index', $caravanRange));
        $this->assertDatabaseMissing('media', [
            'id' => $image->id,
        ]);
    }

    private function submit(CaravanRange $caravanRange, Media $image)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravanRange, $image);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(CaravanRange $caravanRange, Media $image)
    {
        return route('admin.caravan-ranges.interior-gallery-images.destroy', [
            'caravanRange' => $caravanRange,
            'interior_gallery_image' => $image,
        ]);
    }

    private function createCaravanRange()
    {
        return factory(CaravanRange::class)->create();
    }

    private function createImage($caravanRange)
    {
        return factory(Media::class)->create([
            'model_id' => $caravanRange->id,
            'model_type' => CaravanRange::class,
            'collection_name' => 'interiorGallery',
        ]);
    }
}
