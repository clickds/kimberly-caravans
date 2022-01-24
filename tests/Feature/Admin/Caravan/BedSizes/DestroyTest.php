<?php

namespace Tests\Feature\Admin\Caravan\BedSizes;

use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Caravan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroys_bed_size(): void
    {
        $bedSize = $this->createBedSize();

        $response = $this->submit($bedSize);

        $response->assertRedirect(route('admin.caravans.bed-sizes.index', $bedSize->vehicle));
        $this->assertDatabaseMissing('bed_sizes', $bedSize->getAttributes());
    }

    private function submit(BedSize $bedSize): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($bedSize);

        return $this->actingAs($user)->delete($url);
    }

    private function url(BedSize $bedSize): string
    {
        return route('admin.caravans.bed-sizes.destroy', [
            'caravan' => $bedSize->vehicle,
            'bed_size' => $bedSize,
        ]);
    }

    private function createBedSize(?Caravan $caravan = null): BedSize
    {
        if (is_null($caravan)) {
            $caravan = $this->createCaravan();
        }
        $bedDescription = factory(BedDescription::class)->create();
        return $caravan->bedSizes()->create([
            'bed_description_id' => $bedDescription->id,
            'details' => 'some details',
        ]);
    }

    private function createCaravan(array $attributes = []): Caravan
    {
        return factory(Caravan::class)->create($attributes);
    }
}
