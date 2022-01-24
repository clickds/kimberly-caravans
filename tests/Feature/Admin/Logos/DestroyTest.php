<?php

namespace Tests\Feature\Admin\Logos;

use App\Models\Logo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful(): void
    {
        $logo = factory(Logo::class)->create();

        $response = $this->submit($logo);

        $response->assertRedirect(route('admin.logos.index'));

        $this->assertDatabaseMissing('logos', $logo->getAttributes());
    }

    private function submit(Logo $logo): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.logos.destroy', $logo);

        return $this->actingAs($user)->delete($url);
    }
}
