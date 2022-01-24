<?php

namespace Tests\Feature\Admin\Caravan\NightFloorplan;

use App\Models\Caravan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Testing\TestResponse;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $caravan = $this->createCaravan();

        $image = $this->createImage($caravan);

        $this->submit($caravan);

        $this->assertDatabaseMissing('media', [
            'id' => $image->id,
        ]);
    }

    private function submit(Caravan $caravan): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($caravan);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Caravan $caravan): string
    {
        return route('admin.caravans.night-floorplan.destroy', [
            'caravan' => $caravan,
        ]);
    }

    private function createCaravan(): Caravan
    {
        return factory(Caravan::class)->create();
    }

    private function createImage(Caravan $caravan): Media
    {
        return factory(Media::class)->create([
            'model_id' => $caravan->id,
            'model_type' => Caravan::class,
            'collection_name' => 'nightFloorplan',
        ]);
    }
}
