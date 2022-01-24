<?php

namespace Tests\Feature\Admin\MotorhomeRange\ExteriorGalleryImages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\MotorhomeRange;
use App\Models\Site;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $image = $this->createImage($motorhomeRange);

        $response = $this->submit($motorhomeRange, $image);

        $response->assertRedirect(route('admin.motorhome-ranges.exterior-gallery-images.index', $motorhomeRange));
        $this->assertDatabaseMissing('media', [
            'id' => $image->id,
        ]);
    }

    private function submit(MotorhomeRange $motorhomeRange, Media $image)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhomeRange, $image);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(MotorhomeRange $motorhomeRange, Media $image)
    {
        return route('admin.motorhome-ranges.exterior-gallery-images.destroy', [
            'motorhomeRange' => $motorhomeRange,
            'exterior_gallery_image' => $image,
        ]);
    }

    private function createMotorhomeRange()
    {
        return factory(MotorhomeRange::class)->create();
    }

    private function createImage($motorhomeRange)
    {
        return factory(Media::class)->create([
            'model_id' => $motorhomeRange->id,
            'model_type' => MotorhomeRange::class,
            'collection_name' => 'exteriorGallery',
        ]);
    }
}
