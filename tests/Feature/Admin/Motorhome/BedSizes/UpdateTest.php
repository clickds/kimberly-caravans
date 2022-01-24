<?php

namespace Tests\Feature\Admin\Motorhome\BedSizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Motorhome;
use Illuminate\Testing\TestResponse;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_updates_bed_size(): void
    {
        $motorhome = $this->createMotorhome();
        $bedSize = $this->createBedSize($motorhome);
        $data = $this->validData([
            'bed_description_id' => $bedSize->bed_description_id,
        ]);

        $response = $this->submit($bedSize, $data);

        $response->assertRedirect(route('admin.motorhomes.bed-sizes.index', $motorhome));
        $this->assertDatabaseHas('bed_sizes', $data);
    }

    public function test_details_is_required(): void
    {
        $data = $this->validData([
            'details' => null,
        ]);
        $motorhome = $this->createMotorhome();
        $bedSize = $this->createBedSize($motorhome);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('details');
    }

    public function test_bed_description_is_required(): void
    {
        $data = $this->validData([
            'bed_description_id' => null,
        ]);
        $motorhome = $this->createMotorhome();
        $bedSize = $this->createBedSize($motorhome);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_must_exist(): void
    {
        $data = $this->validData([
            'bed_description_id' => 0,
        ]);
        $motorhome = $this->createMotorhome();
        $bedSize = $this->createBedSize($motorhome);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_is_unique(): void
    {
        $motorhome = $this->createMotorhome();
        $bedSize = $this->createBedSize($motorhome);
        $otherBedSize = $this->createBedSize($motorhome);
        $data = $this->validData([
            'bed_description_id' => $otherBedSize->bed_description_id,
        ]);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    private function submit(BedSize $bedSize, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($bedSize);

        return $this->actingAs($user)->put($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'details' => 'some details',
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(BedSize $bedSize): string
    {
        return route('admin.motorhomes.bed-sizes.update', [
            'motorhome' => $bedSize->vehicle,
            'bed_size' => $bedSize,
        ]);
    }

    private function createBedSize(?Motorhome $motorhome = null, ?BedDescription $bedDescription = null): BedSize
    {
        if (is_null($motorhome)) {
            $motorhome = $this->createMotorhome();
        }
        if (is_null($bedDescription)) {
            $bedDescription = $this->createBedDescription();
        }
        return $motorhome->bedSizes()->create([
            'bed_description_id' => $bedDescription->id,
            'details' => 'some details',
        ]);
    }

    private function createBedDescription(): BedDescription
    {
        return factory(BedDescription::class)->create();
    }

    private function createMotorhome(array $attributes = []): Motorhome
    {
        return factory(Motorhome::class)->create($attributes);
    }
}
