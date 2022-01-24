<?php

namespace Tests\Feature\Admin\Navigations;

use App\Models\Navigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->createSite();

        $navigation = factory(Navigation::class)->create();

        $response = $this->submit($navigation);

        $response->assertRedirect(route('admin.navigations.index'));

        $this->assertDatabaseMissing('navigations', ['id' => $navigation->id]);
    }

    private function submit(Navigation $navigation): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($navigation);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Navigation $navigation): string
    {
        return route('admin.navigations.destroy', ['navigation' => $navigation]);
    }
}
