<?php

namespace Tests\Feature\Admin\CaravanRange\InteriorGalleryImages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\CaravanRange;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->fakeStorage();
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields();

        $response = $this->submit($caravanRange, $data);

        $response->assertRedirect(route('admin.caravan-ranges.interior-gallery-images.index', $caravanRange));
        $data = Arr::only($data, ['order_column', 'name']);
        $data = array_merge($data, [
            'collection_name' => "interiorGallery",
            'model_id' => $caravanRange->id,
            'model_type' => CaravanRange::class,
        ]);
        $this->assertDatabaseHas('media', $data);
        $this->assertFileExists($caravanRange->getFirstMedia('interiorGallery')->getPath());
    }

    public function test_name_is_required()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'name' => '',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_image_is_required()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'image' => '',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('image');
    }

    public function test_image_is_an_image()
    {
        $caravanRange = $this->createCaravanRange();
        $data = $this->validFields([
            'image' => 'abc',
        ]);

        $response = $this->submit($caravanRange, $data);

        $response->assertSessionHasErrors('image');
    }

    private function submit(CaravanRange $caravanRange, array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravanRange);

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

    private function url(CaravanRange $caravanRange)
    {
        return route('admin.caravan-ranges.interior-gallery-images.store', [
            'caravanRange' => $caravanRange,
        ]);
    }

    private function createCaravanRange()
    {
        return factory(CaravanRange::class)->create();
    }
}
