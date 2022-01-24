<?php

namespace Tests\Feature\Api\Admin\MotorhomeRange\UploadMultipleGalleryImages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MotorhomeRange;
use Illuminate\Http\UploadedFile;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_with_interior_image()
    {
        $this->fakeStorage();
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields();

        $response = $this->submit($motorhomeRange, $data, 'interior-gallery-images');

        $response->assertStatus(201);
        $expectedData = [
            'collection_name' => "interiorGallery",
            'model_id' => $motorhomeRange->id,
            'model_type' => MotorhomeRange::class,
        ];
        $this->assertDatabaseHas('media', $expectedData);
        $this->assertFileExists($motorhomeRange->getFirstMedia('interiorGallery')->getPath());
    }

    public function test_successful_with_exterior_image()
    {
        $this->fakeStorage();
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields();

        $response = $this->submit($motorhomeRange, $data, 'exterior-gallery-images');

        $response->assertStatus(201);
        $expectedData = [
            'collection_name' => "exteriorGallery",
            'model_id' => $motorhomeRange->id,
            'model_type' => MotorhomeRange::class,
        ];
        $this->assertDatabaseHas('media', $expectedData);
        $this->assertFileExists($motorhomeRange->getFirstMedia('exteriorGallery')->getPath());
    }

    public function test_successful_with_feature_image()
    {
        $this->fakeStorage();
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields();

        $response = $this->submit($motorhomeRange, $data, 'feature-gallery-images');

        $response->assertStatus(201);
        $expectedData = [
            'collection_name' => "featureGallery",
            'model_id' => $motorhomeRange->id,
            'model_type' => MotorhomeRange::class,
        ];
        $this->assertDatabaseHas('media', $expectedData);
        $this->assertFileExists($motorhomeRange->getFirstMedia('featureGallery')->getPath());
    }

    public function test_file_is_required()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'file' => '',
        ]);

        $response = $this->submit($motorhomeRange, $data, 'interior-gallery-images');

        $response->assertSessionHasErrors('file');
    }

    public function test_image_is_an_image()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'file' => 'abc',
        ]);

        $response = $this->submit($motorhomeRange, $data, 'interior-gallery-images');

        $response->assertSessionHasErrors('file');
    }

    private function submit(MotorhomeRange $motorhomeRange, array $data, string $galleryType)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhomeRange, $galleryType);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'file' => UploadedFile::fake()->image('avatar.jpg'),
        ];
        return array_merge($defaults, $overrides);
    }

    private function url(MotorhomeRange $motorhomeRange, string $galleryType)
    {
        return route('api.admin.motorhome-ranges.gallery.upload-multiple.store', [
            'motorhomeRange' => $motorhomeRange,
            'galleryType' => $galleryType,
        ]);
    }

    private function createMotorhomeRange()
    {
        return factory(MotorhomeRange::class)->create();
    }
}
