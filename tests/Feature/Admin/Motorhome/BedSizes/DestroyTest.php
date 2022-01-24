<?php

namespace Tests\Feature\Admin\Motorhome\BedSizes;

use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Motorhome;
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

        $response->assertRedirect(route('admin.motorhomes.bed-sizes.index', $bedSize->vehicle));
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
        return route('admin.motorhomes.bed-sizes.destroy', [
            'motorhome' => $bedSize->vehicle,
            'bed_size' => $bedSize,
        ]);
    }

    private function createBedSize(?Motorhome $motorhome = null): BedSize
    {
        if (is_null($motorhome)) {
            $motorhome = $this->createMotorhome();
        }
        $bedDescription = factory(BedDescription::class)->create();
        return $motorhome->bedSizes()->create([
            'bed_description_id' => $bedDescription->id,
            'details' => 'some details',
        ]);
    }

    private function createMotorhome(array $attributes = []): Motorhome
    {
        return factory(Motorhome::class)->create($attributes);
    }
}
