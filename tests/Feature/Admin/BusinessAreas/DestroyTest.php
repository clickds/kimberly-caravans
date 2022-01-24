<?php

namespace Tests\Feature\Admin\BusinessAreas;

use App\Models\BusinessArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful(): void
    {
        $businessArea = factory(BusinessArea::class)->create();

        $response = $this->submit($businessArea);

        $response->assertRedirect(route('admin.business-areas.index'));
        $this->assertDatabaseMissing('business_areas', $businessArea->getAttributes());
    }

    private function submit(BusinessArea $businessArea): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.business-areas.destroy', $businessArea);

        return $this->actingAs($user)->delete($url);
    }
}
