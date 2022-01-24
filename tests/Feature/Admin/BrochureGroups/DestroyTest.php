<?php

namespace Tests\Feature\Admin\BrochureGroups;

use App\Models\BrochureGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deletes(): void
    {
        $brochureGroup = $this->createBrochureGroup();

        $response = $this->submit($brochureGroup);

        $response->assertRedirect(route('admin.brochure-groups.index'));
        $this->assertDatabaseMissing('brochure_groups', $brochureGroup->getAttributes());
    }

    private function submit(BrochureGroup $brochureGroup): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.brochure-groups.destroy', $brochureGroup);

        return $this->actingAs($user)->delete($url);
    }

    private function createBrochureGroup(array $attributes = []): BrochureGroup
    {
        return factory(BrochureGroup::class)->create($attributes);
    }
}
