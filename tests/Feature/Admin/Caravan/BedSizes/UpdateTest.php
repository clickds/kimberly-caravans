<?php

namespace Tests\Feature\Admin\Caravan\BedSizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Caravan;
use Illuminate\Testing\TestResponse;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_updates_bed_size(): void
    {
        $caravan = $this->createCaravan();
        $bedSize = $this->createBedSize($caravan);
        $data = $this->validData([
            'bed_description_id' => $bedSize->bed_description_id,
        ]);

        $response = $this->submit($bedSize, $data);

        $response->assertRedirect(route('admin.caravans.bed-sizes.index', $caravan));
        $this->assertDatabaseHas('bed_sizes', $data);
    }

    public function test_details_is_required(): void
    {
        $data = $this->validData([
            'details' => null,
        ]);
        $caravan = $this->createCaravan();
        $bedSize = $this->createBedSize($caravan);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('details');
    }

    public function test_bed_description_is_required(): void
    {
        $data = $this->validData([
            'bed_description_id' => null,
        ]);
        $caravan = $this->createCaravan();
        $bedSize = $this->createBedSize($caravan);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_must_exist(): void
    {
        $data = $this->validData([
            'bed_description_id' => 0,
        ]);
        $caravan = $this->createCaravan();
        $bedSize = $this->createBedSize($caravan);

        $response = $this->submit($bedSize, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_is_unique(): void
    {
        $caravan = $this->createCaravan();
        $bedSize = $this->createBedSize($caravan);
        $otherBedSize = $this->createBedSize($caravan);
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
        return route('admin.caravans.bed-sizes.update', [
            'caravan' => $bedSize->vehicle,
            'bed_size' => $bedSize,
        ]);
    }

    private function createBedSize(?Caravan $caravan = null, ?BedDescription $bedDescription = null): BedSize
    {
        if (is_null($caravan)) {
            $caravan = $this->createCaravan();
        }
        if (is_null($bedDescription)) {
            $bedDescription = $this->createBedDescription();
        }
        return $caravan->bedSizes()->create([
            'bed_description_id' => $bedDescription->id,
            'details' => 'some details',
        ]);
    }

    private function createBedDescription(): BedDescription
    {
        return factory(BedDescription::class)->create();
    }

    private function createCaravan(array $attributes = []): Caravan
    {
        return factory(Caravan::class)->create($attributes);
    }
}
