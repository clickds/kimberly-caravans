<?php

namespace Tests\Feature\Admin\Motorhome\BedSizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Motorhome;
use Illuminate\Testing\TestResponse;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_bed_size(): void
    {
        $data = $this->validData();
        $motorhome = $this->createMotorhome();

        $response = $this->submit($motorhome, $data);

        $response->assertRedirect(route('admin.motorhomes.bed-sizes.index', $motorhome));
        $this->assertDatabaseHas('bed_sizes', $data);
    }

    public function test_details_is_required(): void
    {
        $data = $this->validData([
            'details' => null,
        ]);
        $motorhome = $this->createMotorhome();

        $response = $this->submit($motorhome, $data);

        $response->assertSessionHasErrors('details');
    }

    public function test_bed_description_is_required(): void
    {
        $data = $this->validData([
            'bed_description_id' => null,
        ]);
        $motorhome = $this->createMotorhome();

        $response = $this->submit($motorhome, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_must_exist(): void
    {
        $data = $this->validData([
            'bed_description_id' => 0,
        ]);
        $motorhome = $this->createMotorhome();

        $response = $this->submit($motorhome, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_is_unique(): void
    {
        $motorhome = $this->createMotorhome();
        $bedSize = $this->createBedSize($motorhome);
        $data = $this->validData([
            'bed_description_id' => $bedSize->bed_description_id,
        ]);

        $response = $this->submit($motorhome, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    private function submit(Motorhome $motorhome, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($motorhome);

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'details' => 'some details',
        ];
        if (!array_key_exists('bed_description_id', $overrides)) {
            $defaults['bed_description_id'] = $this->createBedDescription()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function url(Motorhome $motorhome): string
    {
        return route('admin.motorhomes.bed-sizes.store', [
            'motorhome' => $motorhome,
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
