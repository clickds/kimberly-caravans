<?php

namespace Tests\Feature\Api\Admin\CaravanRange\UploadMultipleGalleryImages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\CaravanRange;
use Illuminate\Http\UploadedFile;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_with_interior_image()
    {
        $this->fakeStorage();
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields();

        $response = $this->submit($caravanRange, $data, 'interior-gallery-images');

        $response->assertStatus(201);
        $expectedData = [
            'collection_name' => "interiorGallery",
            'model_id' => $caravanRange->id,
            'model_type' => CaravanRange::class,
        ];
        $this->assertDatabaseHas('media', $expectedData);
        $this->assertFileExists($caravanRange->getFirstMedia('interiorGallery')->getPath());
    }

    public function test_successful_with_exterior_image()
    {
        $this->fakeStorage();
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields();

        $response = $this->submit($caravanRange, $data, 'exterior-gallery-images');

        $response->assertStatus(201);
        $expectedData = [
            'collection_name' => "exteriorGallery",
            'model_id' => $caravanRange->id,
            'model_type' => CaravanRange::class,
        ];
        $this->assertDatabaseHas('media', $expectedData);
        $this->assertFileExists($caravanRange->getFirstMedia('exteriorGallery')->getPath());
    }

    public function test_successful_with_feature_image()
    {
        $this->fakeStorage();
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields();

        $response = $this->submit($caravanRange, $data, 'feature-gallery-images');

        $response->assertStatus(201);
        $expectedData = [
            'collection_name' => "featureGallery",
            'model_id' => $caravanRange->id,
            'model_type' => CaravanRange::class,
        ];
        $this->assertDatabaseHas('media', $expectedData);
        $this->assertFileExists($caravanRange->getFirstMedia('featureGallery')->getPath());
    }

    public function test_file_is_required()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'file' => '',
        ]);

        $response = $this->submit($caravanRange, $data, 'interior-gallery-images');

        $response->assertSessionHasErrors('file');
    }

    public function test_image_is_an_image()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'file' => 'abc',
        ]);

        $response = $this->submit($caravanRange, $data, 'interior-gallery-images');

        $response->assertSessionHasErrors('file');
    }

    private function submit(CaravanRange $caravanRange, array $data, string $galleryType)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravanRange, $galleryType);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'file' => UploadedFile::fake()->image('avatar.jpg'),
        ];
        return array_merge($defaults, $overrides);
    }

    private function url(CaravanRange $caravanRange, string $galleryType)
    {
        return route('api.admin.caravan-ranges.gallery.upload-multiple.store', [
            'caravanRange' => $caravanRange,
            'galleryType' => $galleryType,
        ]);
    }

    private function createCaravanRange()
    {
        return factory(CaravanRange::class)->create();
    }
}
