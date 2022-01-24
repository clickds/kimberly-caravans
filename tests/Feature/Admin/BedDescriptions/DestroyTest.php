<?php

namespace Tests\Feature\Admin\BedDescriptions;

use App\Models\BedDescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroys_bed_description(): void
    {
        $bedDescription = factory(BedDescription::class)->create();

        $response = $this->submit($bedDescription);

        $response->assertRedirect(route('admin.bed-descriptions.index'));
        $this->assertDatabaseMissing('bed_descriptions', $bedDescription->getAttributes());
    }

    private function submit(BedDescription $bedDescription): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($bedDescription);

        return $this->actingAs($user)->delete($url);
    }

    private function url(BedDescription $bedDescription): string
    {
        return route('admin.bed-descriptions.destroy', $bedDescription);
    }
}
