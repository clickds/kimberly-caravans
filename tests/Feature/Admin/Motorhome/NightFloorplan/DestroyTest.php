<?php

namespace Tests\Feature\Admin\Motorhome\NightFloorplan;

use App\Models\Motorhome;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Testing\TestResponse;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $motorhome = $this->createMotorhome();

        $image = $this->createImage($motorhome);

        $this->submit($motorhome);

        $this->assertDatabaseMissing('media', [
            'id' => $image->id,
        ]);
    }

    private function submit(Motorhome $motorhome): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($motorhome);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Motorhome $motorhome): string
    {
        return route('admin.motorhomes.night-floorplan.destroy', [
            'motorhome' => $motorhome,
        ]);
    }

    private function createMotorhome(): Motorhome
    {
        return factory(Motorhome::class)->create();
    }

    private function createImage(Motorhome $motorhome): Media
    {
        return factory(Media::class)->create([
            'model_id' => $motorhome->id,
            'model_type' => Motorhome::class,
            'collection_name' => 'nightFloorplan',
        ]);
    }
}
