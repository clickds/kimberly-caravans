<?php

namespace Tests\Feature\Admin\CaravanRange\Caravans;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Caravan;
use App\Models\BedDescription;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $caravan = $this->createCaravan();

        $response = $this->submit($caravan);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        $this->assertDatabaseMissing('caravans', $caravan->getAttributes());
    }

    public function test_deleting_a_caravan_deletes_its_bed_sizes(): void
    {
        $caravan = $this->createCaravan();
        $bedDescription = factory(BedDescription::class)->create();
        $bedSize = $caravan->bedSizes()->create([
            'bed_description_id' => $bedDescription->id,
            'details' => 'some details',
        ]);

        $response = $this->submit($caravan);

        $response->assertRedirect(route('admin.caravan-ranges.caravans.index', $caravan->caravanRange));
        $this->assertDatabaseMissing('caravans', $caravan->getAttributes());
        $this->assertDatabaseMissing('bed_sizes', $bedSize->getAttributes());
    }

    public function test_deleting_a_caravan_deletes_its_features()
    {
        $this->markTestIncomplete();
    }

    private function submit(Caravan $caravan)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($caravan);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Caravan $caravan)
    {
        return route('admin.caravan-ranges.caravans.destroy', [
            'caravanRange' => $caravan->caravanRange,
            'caravan' => $caravan,
        ]);
    }

    private function createCaravan()
    {
        return factory(Caravan::class)->create();
    }
}
