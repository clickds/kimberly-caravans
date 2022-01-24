<?php

namespace Tests\Feature\Admin\Caravan\BedSizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Caravan;
use Illuminate\Testing\TestResponse;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_bed_size(): void
    {
        $data = $this->validData();
        $caravan = $this->createCaravan();

        $response = $this->submit($caravan, $data);

        $response->assertRedirect(route('admin.caravans.bed-sizes.index', $caravan));
        $this->assertDatabaseHas('bed_sizes', $data);
    }

    public function test_details_is_required(): void
    {
        $data = $this->validData([
            'details' => null,
        ]);
        $caravan = $this->createCaravan();

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('details');
    }

    public function test_bed_description_is_required(): void
    {
        $data = $this->validData([
            'bed_description_id' => null,
        ]);
        $caravan = $this->createCaravan();

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_must_exist(): void
    {
        $data = $this->validData([
            'bed_description_id' => 0,
        ]);
        $caravan = $this->createCaravan();

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    public function test_bed_description_is_unique(): void
    {
        $caravan = $this->createCaravan();
        $bedSize = $this->createBedSize($caravan);
        $data = $this->validData([
            'bed_description_id' => $bedSize->bed_description_id,
        ]);

        $response = $this->submit($caravan, $data);

        $response->assertSessionHasErrors('bed_description_id');
    }

    private function submit(Caravan $caravan, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($caravan);

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

    private function url(Caravan $caravan): string
    {
        return route('admin.caravans.bed-sizes.store', [
            'caravan' => $caravan,
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
