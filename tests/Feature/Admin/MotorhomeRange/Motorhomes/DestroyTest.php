<?php

namespace Tests\Feature\Admin\MotorhomeRange\Motorhomes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Motorhome;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $motorhome = $this->createMotorhome();

        $response = $this->submit($motorhome);

        $response->assertRedirect(route('admin.motorhome-ranges.motorhomes.index', $motorhome->motorhomeRange));
        $this->assertDatabaseMissing('motorhomes', [
            'id' => $motorhome->id,
        ]);
    }

    public function test_deleting_a_motorhome_deletes_its_features()
    {
        $this->markTestIncomplete();
    }

    private function submit(Motorhome $motorhome)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($motorhome);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Motorhome $motorhome)
    {
        return route('admin.motorhome-ranges.motorhomes.destroy', [
            'motorhomeRange' => $motorhome->motorhomeRange,
            'motorhome' => $motorhome,
        ]);
    }

    private function createMotorhome()
    {
        return factory(Motorhome::class)->create();
    }
}
