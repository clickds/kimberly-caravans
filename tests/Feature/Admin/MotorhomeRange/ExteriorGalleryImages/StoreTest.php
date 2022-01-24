<?php

namespace Tests\Feature\Admin\MotorhomeRange\ExteriorGalleryImages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MotorhomeRange;
use App\Models\Site;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->fakeStorage();
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields();

        $response = $this->submit($motorhomeRange, $data);

        $response->assertRedirect(route('admin.motorhome-ranges.exterior-gallery-images.index', $motorhomeRange));
        $data = Arr::only($data, ['order_column', 'name']);
        $data = array_merge($data, [
            'collection_name' => "exteriorGallery",
            'model_id' => $motorhomeRange->id,
            'model_type' => MotorhomeRange::class,
        ]);
        $this->assertDatabaseHas('media', $data);
        $this->assertFileExists($motorhomeRange->getFirstMedia('exteriorGallery')->getPath());
    }

    public function test_name_is_required()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_image_is_required()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'image' => '',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('image');
    }

    public function test_image_is_an_image()
    {
        $motorhomeRange = $this->createMotorhomeRange();
        $data = $this->validFields([
            'image' => 'abc',
        ]);

        $response = $this->submit($motorhomeRange, $data);

        $response->assertSessionHasErrors('image');
    }

    private function submit(MotorhomeRange $motorhomeRange, array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhomeRange);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'position' => 0,
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ];
        return array_merge($defaults, $overrides);
    }

    private function url(MotorhomeRange $motorhomeRange)
    {
        return route('admin.motorhome-ranges.exterior-gallery-images.store', [
            'motorhomeRange' => $motorhomeRange,
        ]);
    }

    private function createMotorhomeRange()
    {
        return factory(MotorhomeRange::class)->create();
    }
}
